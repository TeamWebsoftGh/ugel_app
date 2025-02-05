<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\TaxRelief;
use App\Models\TaxReliefDetail;
use App\Repositories\Interfaces\ITaxReliefRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ITaxReliefService;
use Illuminate\Support\Collection;

class TaxReliefService extends ServiceBase implements ITaxReliefService
{
    private $taxReliefRepo;

    /**
     * TaxReliefService constructor.
     *
     * @param ITaxReliefRepository $taxReliefRepository
     */
    public function __construct(ITaxReliefRepository $taxReliefRepository){
        parent::__construct();
        $this->taxReliefRepo = $taxReliefRepository;
    }

    /**
     * List all the TaxReliefs
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listTaxReliefs(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->taxReliefRepo->listTaxReliefs($order, $sort, $columns);
    }

    /**
     * List all the TaxReliefs
     *
     * @param $id
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listTaxReliefDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->findTaxReliefById($id)->taxReliefDetail;
    }

    /**
     * Create TaxRelief
     *
     * @param array $params
     *
     * @return Response
     */
    public function createTaxRelief(array $params)
    {
        //Declaration
        $taxRelief = null;

        //Process Request
        try {

            $taxRelief = $this->taxReliefRepo->createTaxRelief($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new TaxRelief(), 'create-tax_relief-failed');
        }

        //Check if tax_relief was created successfully
        if (!$taxRelief || $taxRelief == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-tax_relief-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $taxRelief, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $taxRelief;

        return $this->response;
    }


    /**
     * Find the TaxRelief by id
     *
     * @param int $id
     *
     * @return TaxRelief
     */
    public function findTaxReliefById(int $id)
    {
        return $this->taxReliefRepo->findTaxReliefById($id);
    }


    /**
     * Update TaxRelief
     *
     * @param array $params
     *
     * @param TaxRelief $taxRelief
     * @return Response
     */
    public function updateTaxRelief(array $params, TaxRelief $taxRelief)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->taxReliefRepo->updateTaxRelief($params, $taxRelief->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $taxRelief, 'update-tax_relief-failed');
        }

        //Check if TaxRelief was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-tax_relief-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $taxRelief, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param TaxRelief $taxRelief
     * @return Response
     */
    public function deleteTaxRelief(TaxRelief $taxRelief)
    {
        //Declaration
        if ($this->taxReliefRepo->deleteTaxRelief($taxRelief->id))
        {
            //Audit Trail
            $logAction = 'delete-tax_relief-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $taxRelief, $logAction);
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
     * @param TaxRelief $taxRelief
     * @return Response
     */
    public function createUpdateTaxReliefDetails(array $data, TaxRelief $taxRelief)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['tax_relief_id'] = $taxRelief->id;

            $result = TaxReliefDetail::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $taxRelief, 'update-tax_relief-detail-failed');
        }

        //Check if Successful
        if (!$result || $result == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-tax_relief-detail-successful';
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
    public function deleteTaxReliefDetail(int $id)
    {
        //Declaration
        $result = false;
        $taxReliefDetail = new TaxReliefDetail();

        try{
            $taxReliefDetail = TaxReliefDetail::findorFail($id);
            $result = $taxReliefDetail->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $taxReliefDetail, 'delete-tax_relief-detail-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-tax_relief-detail-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $taxReliefDetail, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
