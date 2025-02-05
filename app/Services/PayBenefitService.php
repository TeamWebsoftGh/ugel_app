<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\PayBenefit;
use App\Models\PayBenefitDetail;
use App\Repositories\Interfaces\IPayBenefitRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPayBenefitService;
use Illuminate\Support\Collection;

class PayBenefitService extends ServiceBase implements IPayBenefitService
{
    private $payBenefitRepo;

    /**
     * PayBenefitService constructor.
     *
     * @param IPayBenefitRepository $benefitRepository
     */
    public function __construct(IPayBenefitRepository $benefitRepository){
        parent::__construct();
        $this->payBenefitRepo = $benefitRepository;
    }

    /**
     * List all the PayBenefits
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayBenefits(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->payBenefitRepo->listPayBenefits($order, $sort, $columns);
    }

    /**
     * List all the PayBenefits
     *
     * @param $id
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayBenefitsDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->findPayBenefitById($id)->payBenefitDetail;
    }

    /**
     * Create PayBenefit
     *
     * @param array $params
     *
     * @return Response
     */
    public function createPayBenefit(array $params)
    {
        //Declaration
        $payBenefit = null;

        //Process Request
        try {
            if($params['benefit_type'] == 'basic-salary')
            {
                $basic = $this->listPayBenefits()->where('benefit_type', 'basic-salary')
                    ->where('is_active', 1);
                if(count($basic) > 0)
                {
                    $this->response->status = ResponseType::ERROR;
                    $this->response->message = ResponseMessage::DEFAULT_DUPLICATE_ERROR;

                    return $this->response;
                }
            }
            $payBenefit = $this->payBenefitRepo->createPayBenefit($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new PayBenefit(), 'create-pay-benefit-failed');
        }

        //Check if pay-benefit was created successfully
        if (!$payBenefit)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-pay-benefit-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $payBenefit, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $payBenefit;

        return $this->response;
    }


    /**
     * Find the PayBenefit by id
     *
     * @param int $id
     *
     * @return PayBenefit
     */
    public function findPayBenefitById(int $id)
    {
        return $this->payBenefitRepo->findPayBenefitById($id);
    }


    /**
     * Update PayBenefit
     *
     * @param array $params
     *
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function updatePayBenefit(array $params, PayBenefit $payBenefit)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->payBenefitRepo->updatePayBenefit($params, $payBenefit->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $payBenefit, 'update-pay-benefit-failed');
        }

        //Check if PayBenefit was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-pay-benefit-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $payBenefit, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function deletePayBenefit(PayBenefit $payBenefit)
    {
        //Declaration
        if ($this->payBenefitRepo->deletePayBenefit($payBenefit->id))
        {
            //Audit Trail
            $logAction = 'delete-pay-benefit-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $payBenefit, $logAction);
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
     * @param PayBenefit $payBenefit
     * @return Response
     */
    public function createUpdatePayBenefitDetails(array $data, PayBenefit $payBenefit)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if($payBenefit->benefit_type == 'basic-salary' || $payBenefit->benefit_type == 'overtime')
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::NO_ELEMENT_DETAILS;

                return $this->response;
            }

            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['pay-benefit_id'] = $payBenefit->id;

            $result = PayBenefitDetail::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $payBenefit, 'update-pay-benefit-detail-failed');
        }

        //Check if Successful
        if (!$result || $result == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-pay-benefit-detail-successful';
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
    public function deletePayBenefitDetail(int $id)
    {
        //Declaration
        $result = false;
        $payBenefitDetail = new PayBenefitDetail();

        try{
            $payBenefitDetail = PayBenefitDetail::findorFail($id);
            $result = $payBenefitDetail->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $payBenefitDetail, 'delete-pay-benefit-detail-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-pay-benefit-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $payBenefitDetail, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
