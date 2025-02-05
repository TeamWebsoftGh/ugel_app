<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\PayDeduction;
use App\Models\PayDeductionDetail;
use App\Repositories\Interfaces\IPayDeductionRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPayDeductionService;
use Illuminate\Support\Collection;

class PayDeductionService extends ServiceBase implements IPayDeductionService
{
    private $payDeductionRepo;

    /**
     * PayDeductionService constructor.
     *
     * @param IPayDeductionRepository $DeductionRepository
     */
    public function __construct(IPayDeductionRepository $DeductionRepository){
        parent::__construct();
        $this->payDeductionRepo = $DeductionRepository;
    }

    /**
     * List all the PayDeductions
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayDeductions(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->payDeductionRepo->listPayDeductions($order, $sort, $columns);
    }

    /**
     * List all the PayDeductions
     *
     * @param $id
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayDeductionsDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->findPayDeductionById($id)->payDeductionDetail;
    }

    /**
     * Create PayDeduction
     *
     * @param array $params
     *
     * @return Response
     */
    public function createPayDeduction(array $params)
    {
        //Declaration
        $payDeduction = null;

        //Process Request
        try {
            if($params['deduction_type'] == 'basic-salary')
            {
                $basic = $this->listPayDeductions()->where('deduction_type', 'basic-salary')
                    ->where('is_active', 1);
                if(count($basic) > 0)
                {
                    $this->response->status = ResponseType::ERROR;
                    $this->response->message = ResponseMessage::DEFAULT_DUPLICATE_ERROR;

                    return $this->response;
                }
            }
            $payDeduction = $this->payDeductionRepo->createPayDeduction($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new PayDeduction(), 'create-pay-deduction-failed');
        }

        //Check if pay-Deduction was created successfully
        if (!$payDeduction || $payDeduction == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-pay-deduction-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $payDeduction, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payDeduction;

        return $this->response;
    }


    /**
     * Find the PayDeduction by id
     *
     * @param int $id
     *
     * @return PayDeduction
     */
    public function findPayDeductionById(int $id)
    {
        return $this->payDeductionRepo->findPayDeductionById($id);
    }


    /**
     * Update PayDeduction
     *
     * @param array $params
     *
     * @param PayDeduction $payDeduction
     * @return Response
     */
    public function updatePayDeduction(array $params, PayDeduction $payDeduction)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->payDeductionRepo->updatePayDeduction($params, $payDeduction->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $payDeduction, 'update-pay-deduction-failed');
        }

        //Check if PayDeduction was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-pay-Deduction-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $payDeduction, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param PayDeduction $payDeduction
     * @return Response
     */
    public function deletePayDeduction(PayDeduction $payDeduction)
    {
        //Declaration
        if ($this->payDeductionRepo->deletePayDeduction($payDeduction->id))
        {
            //Audit Trail
            $logAction = 'delete-pay-deduction-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $payDeduction, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
    }


    /**
     * @param array $data
     * @param PayDeduction $payDeduction
     * @return Response
     */
    public function createUpdatePayDeductionDetails(array $data, PayDeduction $payDeduction)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['pay-deduction_id'] = $payDeduction->id;

            $result = PayDeductionDetail::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $payDeduction, 'update-pay-deduction-detail-failed');
        }

        //Check if Successful
        if (!$result || $result == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-pay-deduction-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

        log_activity($auditMessage, $result, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $result;

        return $this->response;
    }

    /**
     * @param int $id
     * @return Response
     */
    public function deletePayDeductionDetail(int $id)
    {
        //Declaration
        $result = false;
        $payDeductionDetail = new PayDeductionDetail();

        try{
            $payDeductionDetail = PayDeductionDetail::findorFail($id);
            $result = $payDeductionDetail->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $payDeductionDetail, 'delete-pay-deduction-detail-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-pay-deduction-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $payDeductionDetail, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
