<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Models\Memo\Meeting;
use App\Notifications\MeetingNotify;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\Interfaces\IMeetingRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IMeetingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class MeetingService extends ServiceBase implements IMeetingService
{
    private IMeetingRepository $meetingRepo;
    private IEmployeeRepository $employeeRepo;

    /**
     * MeetingService constructor.
     * @param IMeetingRepository $meeting
     */
    public function __construct(IMeetingRepository $meeting, IEmployeeRepository $employeeRepository)
    {
        parent::__construct();
        $this->meetingRepo = $meeting;
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listMeetings(string $orderBy = 'id', string $sortBy = 'asc', array $columns = ['*']) : Collection
    {
        return $this->meetingRepo->listMeetings($orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createMeeting(array $data)
    {
        //Declaration
        $meeting = null;
        try{
            //Prepare request
            $meeting = $this->meetingRepo->createMeeting($data);

            if($data['is_notify'] == 1 && $data ['status']== 'approved' )
            {
                $emp_data['filter_department'] = $data['department_id'];
                $emp_data['filter_subsidiary'] = $data['subsidiary_id'];
                $emp_data['filter_branch'] = $data['branch_id'];

                $employee_ids = $this->employeeRepo->listEmployees($emp_data)->pluck('id');
                $notifiable = User::whereIn('id', $employee_ids)->get();

                Notification::send($notifiable, new MeetingNotify($data));
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Meeting(), 'create-meeting-failed');
        }

        //Check if Successful
        if ($meeting == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-meeting-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $meeting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $meeting;

        return $this->response;
    }


    /**
     * @param array $data
     * @param Meeting $meeting
     * @return Response
     */
    public function updateMeeting(array $data, Meeting $meeting)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->meetingRepo->updateMeeting($data, $meeting);

            if($data['is_notify'] == 1 && $data ['status']== 'approved' )
            {
                $emp_data['filter_department'] = $data['department_id'];
                $emp_data['filter_subsidiary'] = $data['subsidiary_id'];
                $emp_data['filter_branch'] = $data['branch_id'];

                $employee_ids = $this->employeeRepo->listEmployees($emp_data)->pluck('id');
                $notifiable = User::whereIn('id', $employee_ids)->get();

                Notification::send($notifiable, new MeetingNotify($data));
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), new Meeting(), 'create-meeting-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-meeting-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $meeting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $meeting;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Meeting|null
     */
    public function findMeetingById(int $id)
    {
        return $this->meetingRepo->findMeetingById($id);
    }


    /**
     * @param Meeting $meeting
     * @return Response
     */
    public function deleteMeeting(Meeting $meeting)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->meetingRepo->deleteMeeting($meeting);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $meeting, 'delete-meeting-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-meeting-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $meeting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
