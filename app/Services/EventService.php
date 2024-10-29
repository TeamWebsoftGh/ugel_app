<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Models\Memo\Event;
use App\Notifications\EventNotify;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\Interfaces\IEventRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IEventService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class EventService extends ServiceBase implements IEventService
{
    private IEventRepository $eventRepo;
    private IEmployeeRepository $employeeRepo;

    /**
     * EventService constructor.
     * @param IEventRepository $event
     */
    public function __construct(IEventRepository $event, IEmployeeRepository $employeeRepository)
    {
        parent::__construct();
        $this->eventRepo = $event;
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listEvents(string $orderBy = 'id', string $sortBy = 'asc', array $columns = ['*']) : Collection
    {
        return $this->eventRepo->listEvents($orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createEvent(array $data)
    {
        //Declaration
        $event = null;
        try{
            //Prepare request
            $event = $this->eventRepo->createEvent($data);

            if($data['is_notify'] == 1 && $data ['status']== 'approved' )
            {
                $emp_data['filter_department'] = $data['department_id'];
                $emp_data['filter_subsidiary'] = $data['subsidiary_id'];
                $emp_data['filter_branch'] = $data['branch_id'];

                $employee_ids = $this->employeeRepo->listEmployees($emp_data)->pluck('id');
                $notifiable = User::whereIn('id', $employee_ids)->get();

                Notification::send($notifiable, new EventNotify($data));
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Event(), 'create-event-failed');
        }

        //Check if Successful
        if ($event == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-event-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $event, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $event;

        return $this->response;
    }


    /**
     * @param array $data
     * @param Event $event
     * @return Response
     */
    public function updateEvent(array $data, Event $event)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->eventRepo->updateEvent($data, $event);

            if($data['is_notify'] == 1 && $data ['status']== 'approved' )
            {
                $emp_data['filter_department'] = $data['department_id'];
                $emp_data['filter_subsidiary'] = $data['subsidiary_id'];
                $emp_data['filter_branch'] = $data['branch_id'];

                $employee_ids = $this->employeeRepo->listEmployees($emp_data)->pluck('id');
                $notifiable = User::whereIn('id', $employee_ids)->get();

                Notification::send($notifiable, new EventNotify($data));
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Event(), 'create-event-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-event-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $event, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $event;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Event|null
     */
    public function findEventById(int $id)
    {
        return $this->eventRepo->findEventById($id);
    }


    /**
     * @param Event $event
     * @return Response
     */
    public function deleteEvent(Event $event)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->eventRepo->deleteEvent($event);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $event, 'delete-event-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-event-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $event, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
