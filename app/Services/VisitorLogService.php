<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\CustomerService\VisitorLog;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\Interfaces\IVisitorLogRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IVisitorLogService;
use Illuminate\Support\Collection;

class VisitorLogService extends ServiceBase implements IVisitorLogService
{
    private IVisitorLogRepository $visitorLogRepo;
    private IUserRepository $userRepo;

    /**
     * SectionService constructor.
     * @param IVisitorLogRepository $visitorLog
     * @param IUserRepository $user
     */
    public function __construct(IVisitorLogRepository $visitorLog, IUserRepository $user)
    {
        parent::__construct();
        $this->visitorLogRepo = $visitorLog;
        $this->userRepo = $user;
    }

    /**
     * @param string $orderBy
     * @param string $sortBy
     *
     * @param array $columns
     * @return Collection
     */
    public function listVisitorLogs(array $filter, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        if(!user()->can("read-visitor-logs"))
        {
            $filter['employee_id'] = employee()->id;
        }
        return $this->visitorLogRepo->listVisitorLogs($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @param bool $send_mail
     * @return Response
     */
    public function createVisitorLog(array $params, bool $send_mail = true): Response
    {
        //Declaration
        $visitorLog = null;
        try{
            //Prepare request
            $visitorLog = $this->visitorLogRepo->createVisitorLog($params);

            //$notifiable = $this->userRepo->find($params['employee_id']);

            //$notifiable->notify(new EmployeeVisitorLogNotify());

        }catch (\Exception $ex){
            log_error(format_exception($ex), new VisitorLog(), 'create-visitor-log-failed');
        }

        //Check if Successful
        if ($visitorLog == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-visitor-log-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $visitorLog, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $visitorLog;

        return $this->response;
    }

    /**
     * @param array $data
     * @param VisitorLog $visitorLog
     * @return Response
     */
    public function updateVisitorLog(array $data, VisitorLog $visitorLog)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->visitorLogRepo->updateVisitorLog($data, $visitorLog);
          //  $notifiable = $this->userRepo->find($data['employee_id']);

           // $notifiable->notify(new EmployeeVisitorLogNotify());

        }catch (\Exception $ex){
            log_error(format_exception($ex), $visitorLog, 'update-visitor-log-failed');
        }

        if (!isset($result) || !$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-visitor-log-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $visitorLog, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $visitorLog;

        return $this->response;
    }


    /**
     * @param int $id
     * @return VisitorLog|null
     */
    public function findVisitorLogById(int $id) : VisitorLog
    {
        return $this->visitorLogRepo->findOneOrFail($id);
    }

    /**
     * @param VisitorLog $visitorLog
     * @return Response
     */
    public function deleteVisitorLog(VisitorLog $visitorLog)
    {
        //Declaration
        $result = false;

        try{
            $employee = $this->employeeRepo->find($visitorLog->employee_id);
            $result = $this->visitorLogRepo->deleteVisitorLog($visitorLog);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $visitorLog, 'delete-visitor-log-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-visitor-log-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $visitorLog, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param string $staff_id
     * @return mixed
     */
    public function findVisitorLogByStaffId(string $staff_id)
    {
        return $this->visitorLogRepo->findOneByOrFail(['staff_id' => $staff_id]);
    }

}
