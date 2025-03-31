<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Termination;
use App\Notifications\EmployeeExitNotification;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\Interfaces\IClientRepository;
use App\Repositories\Interfaces\ITerminationRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ITerminationService;
use Illuminate\Support\Collection;

class TerminationService extends ServiceBase implements ITerminationService
{
    private ITerminationRepository $terminationRepo;
    private IClientRepository $employeeRepo;
    private IUserRepository $userRepo;

    /**
     * SectionService constructor.
     * @param IterminationRepository $termination
     * @param IClientRepository $employee
     * @param IUserRepository $user
     */
    public function __construct(IterminationRepository $termination, IClientRepository $employee, IUserRepository $user)
    {
        parent::__construct();
        $this->terminationRepo = $termination;
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
    public function listTerminations(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->terminationRepo->listTerminations($filter, $order, $sort, $columns);
    }

    /**
     * @param array $params
     *
     * @param bool $send_mail
     * @return Response
     */
    public function createTermination(array $params, bool $send_mail = true): Response
    {
        //Declaration
        $termination = null;
        try{
            //Prepare request
            $employee = $this->employeeRepo->listExitedEmployees()->where('id', '==', $params['employee_id']);

            if ($employee->count() > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Employee already exited.";

                return $this->response;
            }
            $employee = $this->employeeRepo->find($params['employee_id']);
            $this->employeeRepo->updateEmployee(['exit_date' => $params['termination_date']], $employee);
            $termination = $this->terminationRepo->createTermination($params);

            $notifiable = $this->userRepo->find($params['employee_id']);
            $notifiable->is_active = 0;
            $notifiable->save();

            $notifiable->notify(new EmployeeExitNotification($termination));

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Termination(), 'exit-employee-failed');
        }

        //Check if Successful
        if ($termination == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'exit-employee-successful';
        $auditMessage ='Employee exited successfully. Employee: '.$employee->FullName;

        log_activity($auditMessage, $termination, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $termination;

        return $this->response;
    }

    /**
     * @param array $data
     * @param Termination $termination
     * @return Response
     */
    public function updateTermination(array $data, Termination $termination)
    {
        //Declaration
        $result = false;

        try{
            $employee = $this->employeeRepo->listExitedEmployees()->find($data['employee_id']);

            if ($termination->termination_date != $employee->exit_date){
                $this->employeeRepo->updateEmployee(['exit_date' => $data['termination_date']], $employee);
            }
            $result = $this->terminationRepo->updateTermination($data, $termination);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $termination, 'exit-employee-update-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'exit-employee-update-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $termination, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $termination;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Termination|null
     */
    public function findTerminationById(int $id) : Termination
    {
        return $this->terminationRepo->findOneOrFail($id);
    }

    /**
     * @param Termination $termination
     * @return Response
     */
    public function deleteTermination(Termination $termination)
    {
        //Declaration
        $result = false;

        try{
            $employee = $this->employeeRepo->listExitedEmployees()->firstWhere('id', '==', $termination->employee_id);
            $result = $this->employeeRepo->updateEmployee(['exit_date' => null], $employee);

            $result = $this->terminationRepo->deleteTermination($termination);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $termination, 'exit-employee-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'exit-employee-delete-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $termination, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findTerminationByStaffId(string $staff_id)
    {
        return $this->terminationRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }
}
