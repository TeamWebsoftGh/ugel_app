<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\IncomeTax;
use App\Models\IncomeTaxTable;
use App\Repositories\Interfaces\IIncomeTaxRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IIncomeTaxService;
use Illuminate\Support\Collection;

class IncomeTaxService extends ServiceBase implements IIncomeTaxService
{
    private $incomeTaxRepo;

    /**
     * IncomeTaxService constructor.
     * @param IIncomeTaxRepository $incomeTax
     */
    public function __construct(IIncomeTaxRepository $incomeTax)
    {
        parent::__construct();
        $this->incomeTaxRepo = $incomeTax;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listIncomeTaxes(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->incomeTaxRepo->listIncomeTaxes($orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createIncomeTax(array $data)
    {
        //Declaration
        $incomeTax = null;

        try{
            //Process Request
            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $incomeTax = $this->incomeTaxRepo->createIncomeTax($data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new IncomeTax(), 'create-income-tax-failed');
       }

        //Check if Successful
        if (!$incomeTax || $incomeTax == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-income-tax-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $incomeTax, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $incomeTax;

        return $this->response;
    }


    /**
     * @param array $data
     * @param IncomeTax $incomeTax
     * @return Response
     */
    public function updateIncomeTax(array $data, IncomeTax $incomeTax)
    {
        //Declaration
        $result = false;

        try{
            if(isset($data['status']))
                $data['is_active'] = $data['status'];
            $result = $this->incomeTaxRepo->updateIncomeTax($data, $incomeTax);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $incomeTax, 'update-income-tax-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-income-tax-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $incomeTax, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $incomeTax;

        return $this->response;
    }

    /**
     * @param int $id
     * @return IncomeTax|null
     */
    public function findIncomeTaxById($id): ?IncomeTax
    {
        return $this->incomeTaxRepo->findIncomeTaxById($id);
    }

    /**
     * @param IncomeTax $incomeTax
     * @return Response
     */
    public function deleteIncomeTax(IncomeTax $incomeTax)
    {
        //Declaration
        $result = false;

        try{
            $incomeTax->incomeTaxTable()->delete();
            $result = $this->incomeTaxRepo->deleteIncomeTax($incomeTax);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $incomeTax, 'delete-income-tax-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-income-tax-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $incomeTax, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function taxTable($id)
    {
        return IncomeTaxTable::all()->where('income_tax_id', $id);
    }

    /**
     * @param array $data
     * @param IncomeTax $incomeTax
     * @return Response
     */
    public function createUpdateIncomeTaxTable(array $data, IncomeTax $incomeTax)
    {
        //Declaration
        $result = null;

        try{
            //Process Request
            if(isset($data['status']))
                $data['is_active'] = $data['status'];

            $data['min_amount'] = $data['chargeable_income'];
            $data['type'] = $data['description'];
            $data['income_tax_id'] = $incomeTax->id;

            $result = IncomeTaxTable::updateOrCreate(['id' => $data['id']], $data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new IncomeTax(), 'update-income-tax-table-failed');
        }

        //Check if Successful
        if (!$result || $result == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-income-tax-table-successful';
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
    public function deleteIncomeTaxTable(int $id)
    {
        //Declaration
        $result = false;
        $incomeTaxTable = new IncomeTaxTable();

        try{
            $incomeTaxTable = IncomeTaxTable::findorFail($id);
            $result = $incomeTaxTable->delete();
        }catch (\Exception $ex){
            log_error(format_exception($ex), $incomeTaxTable, 'delete-income-tax-table-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-income-tax-table-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $incomeTaxTable, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
