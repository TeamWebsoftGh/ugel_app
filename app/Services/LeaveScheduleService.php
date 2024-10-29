<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Employees\Employee;
use App\Models\Timesheet\LeaveSchedule;
use App\Models\Timesheet\LeaveType;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\Interfaces\ILeaveScheduleRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ILeaveScheduleService;
use App\Traits\LeaveTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeaveScheduleService extends ServiceBase implements ILeaveScheduleService
{
    use LeaveTrait;

    private ILeaveScheduleRepository $leaveScheduleRepo;
    private IEmployeeRepository $employeeRepo;

    /**
     * LeaveScheduleService constructor.
     *
     * @param ILeaveScheduleRepository $leaveScheduleRepo
     */
    public function __construct(ILeaveScheduleRepository $leaveScheduleRepo, IEmployeeRepository $employeeRepository){
        parent::__construct();
        $this->leaveScheduleRepo = $leaveScheduleRepo;
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * List all the LeaveSchedules
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveSchedules(array $filter = null, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        if(!user()->can("read-leave-schedules"))
        {
            $filter['employee_id'] = employee()->deprtment_id;
        }

        return $this->leaveScheduleRepo->listLeaveSchedules($filter, $order, $sort, $columns);
    }


    /**
     * Create LeaveSchedule
     *
     * @param array $params
     *
     * @return Response
     */
    public function createLeaveSchedule(array $params)
    {
        //Declaration
        $leaveSchedule = null;
        $year = Carbon::now()->year;
        $leaveSchedule_ty = LeaveSchedule::query()
            ->where('employee_id', $params['employee_id'])
            ->where('leave_year', $year)
            ->where('leave_type_id', $params['leave_type']);

        //Process Request
        try {
            $employee = $this->employeeRepo->find($params['employee_id']);
            $leaveScheduleType = $this->getLeaveType($employee, $params['leave_type']);

            $total_leaveSchedule1 = $leaveSchedule_ty->where('status','approved')->sum('total_days');

            $total_leaveSchedule = $total_leaveSchedule1 + $params['total_days'];

            $startDate = Carbon::parse($params['start_date']);
            $params['leave_year'] = $startDate->year;

            if($total_leaveSchedule > $leaveScheduleType->allocated_days)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "The remaining leave days are insufficient";
                return $this->response;
            }

            if(user()->can("create-leave-schedules"))
            {
                $params['status'] = "submitted";
            }
            $params['total_days_before'] = $total_leaveSchedule1;
            $params['total_days_after'] = $total_leaveSchedule;
            $leaveSchedule = $this->leaveScheduleRepo->createLeaveSchedule($params);

            if($leaveSchedule == null){
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;
                return  $this->response;
            }
//            if($leaveSchedule->is_notify)
//            {
//                $text = "A new leaveSchedule notification has been published";
//                $notifiable = User::findOrFail($employee->id);
//                $notifiable->notify(new LeaveScheduleNotification($text)); //To Employee
//            }
        } catch (\Exception $e) {
            log_error(format_exception($e), new LeaveSchedule(), 'create-leave-schedule-failed');
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-leave-schedule-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $leaveSchedule, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $leaveSchedule;

        return $this->response;
    }

    /**
     * Find the LeaveSchedule by id
     *
     * @param int $id
     *
     * @return LeaveSchedule
     */
    public function findLeaveScheduleById(int $id)
    {
        return $this->leaveScheduleRepo->findLeaveScheduleById($id);
    }


    /**
     * Update LeaveSchedule
     *
     * @param array $params
     *
     * @param LeaveSchedule $leaveSchedule
     * @return Response
     */
    public function updateLeaveSchedule(array $params, LeaveSchedule $leaveSchedule)
    {
        //Declaration
        $result = false;
        $oldStatus =  $leaveSchedule->status;
        $params['employee_id'] = $leaveSchedule->employee_id;

        //Process Request
        try {
            $leaveScheduleType = $this->getLeaveType($leaveSchedule->employee, $params['leave_type']);
            $total_leaveSchedule = LeaveSchedule::query()
                ->where('employee_id', $leaveSchedule->employee_id)
                ->where('leave_type_id', $params['leave_type'])
                ->where('status','approved')
                ->sum('total_days');

            $total_leaveSchedule = $total_leaveSchedule + $params['total_days'];

            $startDate = Carbon::parse($params['start_date']);
            $params['leave_year'] = $startDate->year;

            if($total_leaveSchedule > $leaveScheduleType->allocated_days)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "The remaining leave days are insufficient";
                return $this->response;
            }
            $result = $this->leaveScheduleRepo->updateLeaveSchedule($params, $leaveSchedule->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $leaveSchedule, 'update-leave-schedule-failed');
        }

        //Check if LeaveSchedule was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-leave-schedule-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $leaveSchedule, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param LeaveSchedule $leaveSchedule
     * @return Response
     */
    public function deleteLeaveSchedule(LeaveSchedule $leaveSchedule)
    {
        //Declaration
        if ($this->leaveScheduleRepo->deleteLeaveSchedule($leaveSchedule->id))
        {
            //Audit Trail
            $logAction = 'delete-leave-schedule-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $leaveSchedule, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
    }

    public function getAnnualLeave(Employee $employee)
    {
        return $this->getLeaveType($employee)?->id;
    }

    public function getLeaveScheduleDaysRemaining($leaveTypeId, $employeeIid)
    {
        $employee = Employee::find($employeeIid);
        $leaveScheduleType = LeaveType::find($leaveTypeId);

        $total_leaveSchedule = DB::table('leaveSchedules')
            ->where('employee_id', $employeeIid)
            ->where('leave_type_id', $leaveTypeId)
            ->sum('total_days');

        if ($employee->ServiceMonths > $leaveScheduleType->minimum_service_month)
        {
            return 0;
        }

        return $total_leaveSchedule;
    }

    /**
     * @param array $data
     * @return array
     */

    public function getCreateLeaveSchedule(array $data = null)
    {
        if(isset($data['leave_schedule_id']))
        {
            $leaveSchedule = $this->findLeaveScheduleById($data['leave_schedule_id']);
            $employee = $leaveSchedule->employee;
            $show_emp = false;

        }else{
            $leaveSchedule = new LeaveSchedule();
            $employee = $this->employeeRepo->find(user()->id)?? new Employee();
            $show_emp = user()->can('create-leave-schedules');;
        }

        $leaveSchedule_types = $this->getLeaveTypes($employee);
        $annual = $this->getAnnualLeave($employee)??new LeaveType();

        return [
            'show_employees' => $show_emp,
            'employee' => $employee,
            'leave_schedule' => $leaveSchedule,
            'annual' => $annual,
            'leave_types' => $leaveSchedule_types,
        ];
    }
}
