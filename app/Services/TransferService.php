<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Transfer;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\Interfaces\IClientRepository;
use App\Repositories\Interfaces\ITransferRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ITransferService;
use Illuminate\Support\Collection;

class TransferService extends ServiceBase implements ITransferService
{
    private ITransferRepository $transferRepo;
    private IClientRepository $employeeRepo;
    private IUserRepository $userRepo;

    /**
     * SectionService constructor.
     * @param ITransferRepository $transfer
     * @param IClientRepository $employee
     * @param IUserRepository $user
     */
    public function __construct(ITransferRepository $transfer, IClientRepository $employee, IUserRepository $user)
    {
        parent::__construct();
        $this->transferRepo = $transfer;
        $this->employeeRepo = $employee;
        $this->userRepo = $user;
    }

    /**
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @return Collection
     */
    public function listTransfers(array $filter, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        if(!user()->can("read-transfers"))
        {
            $filter['employee_id'] = employee()->id;
        }
        return $this->transferRepo->listTransfers($filter, $orderBy, $sortBy, $columns);
    }

    /**
     * @param array $params
     *
     * @param bool $send_mail
     * @return Response
     */
    public function createTransfer(array $params, bool $send_mail = true): Response
    {
        //Declaration
        $transfer = null;
        try{
            //Prepare request
            $employee = $this->employeeRepo->find($params['employee_id']);

            $params['from_department_id'] = $employee->department_id;
            $params['from_location_id'] = $employee->location_id;
            $transfer = $this->transferRepo->createTransfer($params);

            if(isset($params['to_department_id']) && !is_null($params['to_department_id']))
                $this->employeeRepo->updateEmployee(['department_id' => $params['to_department_id']], $employee);
            if(isset($params['to_location_id']) && !is_null($params['to_location_id']))
                $this->employeeRepo->updateEmployee(['location_id' => $params['to_location_id']], $employee);

            $notifiable = $this->userRepo->find($params['employee_id']);

            //$notifiable->notify(new EmployeeTransferNotify());

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Transfer(), 'transfer-employee-failed');
        }

        //Check if Successful
        if ($transfer == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'transfer-employee-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

        log_activity($auditMessage, $transfer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $transfer;

        return $this->response;
    }

    /**
     * @param array $data
     * @param Transfer $transfer
     * @return Response
     */
    public function updateTransfer(array $data, Transfer $transfer)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->transferRepo->updateTransfer($data, $transfer);

            $employee = $this->employeeRepo->find($data['employee_id']);
            if(isset($data['to_department_id']) && !is_null($data['to_department_id']))
                $this->employeeRepo->updateEmployee(['department_id' => $data['to_department_id']], $employee);
            if(isset($data['to_location_id']) && !is_null($data['to_location_id']))
                $this->employeeRepo->updateEmployee(['location_id' => $data['to_location_id']], $employee);

            $notifiable = $this->userRepo->find($data['employee_id']);

           // $notifiable->notify(new EmployeeTransferNotify());

        }catch (\Exception $ex){
            log_error(format_exception($ex), $transfer, 'transfer-employee-update-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'transfer-employee-update-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $transfer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $transfer;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Transfer|null
     */
    public function findTransferById(int $id) : Transfer
    {
        return $this->transferRepo->findOneOrFail($id);
    }

    /**
     * @param Transfer $transfer
     * @return Response
     */
    public function deleteTransfer(Transfer $transfer)
    {
        //Declaration
        $result = false;

        try{
            $employee = $this->employeeRepo->find($transfer->employee_id);

            if(isset($transfer->to_department_id))
                $this->employeeRepo->updateEmployee(['department_id' => $transfer->from_department_id], $employee);
            if(isset($transfer->to_location_id))
                $this->employeeRepo->updateEmployee(['location_id' => $transfer->from_location_id], $employee);

            $result = $this->transferRepo->deleteTransfer($transfer);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $transfer, 'transfer-employee-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'transfer-employee-delete-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $transfer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findTransferByStaffId(string $staff_id)
    {
        return $this->transferRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }

}
