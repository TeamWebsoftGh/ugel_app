<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Mail\Employees\LeaveApprovedMail;
use App\Mail\Employees\LeaveSubmittedMail;
use App\Models\Employees\Employee;
use App\Models\Timesheet\Holiday;
use App\Models\Timesheet\Leave;
use App\Models\Timesheet\LeaveBalance;
use App\Models\Timesheet\LeaveType;
use App\Repositories\Interfaces\IClientRepository;
use App\Repositories\Interfaces\ILeaveRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ILeaveService;
use App\Traits\LeaveTrait;
use App\Utilities\WorkflowUtil;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LeaveService extends ServiceBase implements ILeaveService
{
    use LeaveTrait, WorkflowUtil;

    private ILeaveRepository $leaveRepo;
    private IClientRepository $employeeRepo;

    /**
     * LeaveService constructor.
     *
     * @param ILeaveRepository $leaveRepo
     */
    public function __construct(ILeaveRepository $leaveRepo, IClientRepository $employeeRepository){
        parent::__construct();
        $this->leaveRepo = $leaveRepo;
        $this->employeeRepo = $employeeRepository;
    }

    /**
     * List all the Leaves
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaves(array $filter = null, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        if(!user()->can("read-leaves"))
        {
            $filter['employee_id'] = employee()->deprtment_id;
        }

        return $this->leaveRepo->listLeaves($filter, $order, $sort, $columns);
    }

    /**
     * List all the Leaves
     *
     * @param $id
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveBalances($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->leaveRepo->listLeaveBalances($id);
    }

    /**
     * Create Leave
     *
     * @param array $params
     *
     * @return Response
     */
    public function createLeave(array $params)
    {
        //Declaration
        $leave = null;
        $year = Carbon::now()->year;
        $leave_ty = Leave::query()
            ->where('employee_id', $params['employee_id'])
            ->where('leave_year', $year)
            ->where('leave_type_id', $params['leave_type']);

        //Process Request
        try {
            $employee = $this->employeeRepo->find($params['employee_id']);
            $leaveType = $this->getLeaveType($employee, $params['leave_type']);

            $pending_leave = $leave_ty->whereIn('status', ['pending', 'submitted'])->count();

            if($pending_leave > 0){
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You already have a leave request pending approval.";
                return $this->response;
            }

            $total_leave1 = $leave_ty->where('status','approved')->sum('total_days');

            $total_leave = $total_leave1 + $params['total_days'];

            $startDate = Carbon::parse($params['start_date']);
            $params['leave_year'] = $startDate->year;

            if($total_leave > $leaveType->allocated_days)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "The remaining leave days are insufficient";
                return $this->response;
            }

            if(user()->can("create-leaves"))
            {
                $params['status'] = "submitted";
            }
            $params['total_days_before'] = $total_leave1;
            $params['total_days_after'] = $total_leave;
            $leave = $this->leaveRepo->createLeave($params);

            if($leave == null){
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;
            }

            if (isset($params['files'])) {
                $files = collect($params['files']);
                $this->leaveRepo->saveDocuments($files, $leave);
            }

            //Check if leave-type was created successfully
            if($leave->status == "approved"){
                $data = [];
                $data['leave_type'] = $leaveType->id;
                $data['leave_year'] = $params['leave_year'];
                $data['employee_id'] = $employee->id;

                $this->createUpdateLeaveBalances($data);
            }else{
                $employee->email = "jerryjohnc1@gmail.com";
                send_mail(LeaveSubmittedMail::class, $leave, $employee);
            }

            $this->addWorkflowRequest($leave, $employee);

//            if($leave->is_notify)
//            {
//                $text = "A new leave notification has been published";
//                $notifiable = User::findOrFail($employee->id);
//                $notifiable->notify(new LeaveNotification($text)); //To Employee
//            }
        } catch (\Exception $e) {
            log_error(format_exception($e), new Leave(), 'create-leave-failed');
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-leave-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $leave, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $leave;

        return $this->response;
    }

    /**
     * Find the Leave by id
     *
     * @param int $id
     *
     * @return Leave
     */
    public function findLeaveById(int $id)
    {
        return $this->leaveRepo->findLeaveById($id);
    }


    /**
     * Update Leave
     *
     * @param array $params
     *
     * @param Leave $leave
     * @return Response
     */
    public function updateLeave(array $params, Leave $leave)
    {
        //Declaration
        $result = false;
        $oldStatus =  $leave->status;
        $params['employee_id'] = $leave->employee_id;

        //Process Request
        try {
            $leaveType = $this->getLeaveType($leave->employee, $params['leave_type']);
            $total_leave = Leave::query()
                ->where('employee_id', $leave->employee_id)
                ->where('leave_type_id', $params['leave_type'])
                ->where('status','approved')
                ->sum('total_days');

            $total_leave = $total_leave + $params['total_days'];

            $startDate = Carbon::parse($params['start_date']);
            $params['leave_year'] = $startDate->year;

            if($total_leave > $leaveType->allocated_days)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "The remaining leave days are insufficient";
                return $this->response;
            }
            $result = $this->leaveRepo->updateLeave($params, $leave->id);

            if (isset($params['files']))
            {
                $files = collect($params['files']);
                $this->leaveRepo->saveDocuments($files, $leave);
            }

            if($params['status'] == "approved" && $oldStatus != "approved" )
            {
                send_mail(LeaveApprovedMail::class, $leave, $leave->employee);
            }

            $this->addWorkflowRequest($leave, $leave->employee);

            $data = [];
            $data['leave_type'] = $leaveType->id;
            $data['leave_year'] = $params['leave_year'];
            $data['employee_id'] = $leave->employee_id;
            $this->createUpdateLeaveBalances($data);
        } catch (\Exception $e) {
            log_error(format_exception($e), $leave, 'update-leave-failed');
        }

        //Check if Leave was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-leave-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $leave, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Leave $leave
     * @return Response
     */
    public function deleteLeave(Leave $leave)
    {
        //Declaration
        if ($this->leaveRepo->deleteLeave($leave->id))
        {
            //Audit Trail
            $logAction = 'delete-leave-type-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $leave, $logAction);
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
        $date = Carbon::now()->year;
        $data = [];
        $data['leave_type'] = $this->getLeaveType($employee)?->id;
        $data['leave_year'] = $date;
        $data['employee_id'] = $employee->id;

        $this->createUpdateLeaveBalances($data);
        return LeaveBalance::where('employee_id', $employee->id)->where('leave_year', $date)->where('leave_type_id', $data['leave_type'])->first();
    }

    /**
     * @param array $data
     * @return Response
     */
    public function createUpdateLeaveBalances(array $data)
    {
        // Declaration
        $result = null;

        try {
            // Process Request
            $leaveYear = $data['leave_year'];
            $leaveType = $data['leave_type'];
            $startOfYear = Carbon::createFromDate($leaveYear, 1, 1)->startOfYear();
            $endOfYear = Carbon::createFromDate($leaveYear, 12, 31)->endOfYear();

            if (isset($data['employee_id']))
            {
                $employee = $this->employeeRepo->find($data['employee_id']);
                $this->updateLeaveBalance($employee, $leaveType, $leaveYear, $startOfYear, $endOfYear);
            }else{
                $employees = Employee::whereDate('joining_date', '<=', $endOfYear)
                    ->where(function ($query) use ($startOfYear) {
                        $query->where('exit_date', '>=', $startOfYear)
                            ->orWhereNull('exit_date');
                    })->get();

                foreach ($employees as $employee) {
                    $this->updateLeaveBalance($employee, $leaveType, $leaveYear, $startOfYear, $endOfYear);
                }
            }

            // Audit Trail
            $logAction = 'update-leave-balance-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS;

            log_activity($auditMessage, $result, $logAction);

            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;
            $this->response->data = $result;
        } catch (\Exception $ex) {
            log_error(format_exception($ex), new LeaveBalance(), 'update-leave-type-detail-failed');
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
        }

        return $this->response;
    }

    public function getleaveDaysRemaining($leaveTypeId, $employeeIid)
    {
        $employee = Employee::find($employeeIid);
        $leaveType = LeaveType::find($leaveTypeId);

        $total_leave = DB::table('leaves')
            ->where('employee_id', $employeeIid)
            ->where('leave_type_id', $leaveTypeId)
            ->where('status','approved')
            ->sum('total_days');

        if ($employee->ServiceMonths > $leaveType->minimum_service_month)
        {
            return 0;
        }

        return $total_leave;
    }

    /**
     * @return bool
     */
    public function checkForHolidayOrWeekend(array $data)
    {
        $date = Carbon::parse($data['start_date']);
        $hols = Holiday::all()->pluck('start_date')->toArray();
        $holidays = [] ;
        $date_s = $date->toDateString();
        foreach ($hols as $hol) {$holidays[] = Carbon::parse($hol)->toDateString();}
        return (int ) ($date->isWeekend() || in_array($date_s, $holidays)); // Day is a holiday or weekend
    }

    /**
     * @param array $data
     * @return string
     */
    public function getLeaveEndDate(array $data): string
    {
        $startDate = Carbon::parse($data['start_date']);
        $duration = $data['duration'];
        $leaveTypeId = $data['leave_type_id'];
        $leaveType = LeaveType::find($leaveTypeId);
        $myDateCarbon = Carbon::parse($startDate);

        //get holidays array
        $holidays = $this->getHolidaysArray();
        for ($i = 1; $i <= $duration - 1; $i++) {
            $a = true;
            while ($a == true) {
                if ($leaveType->working_days_only) {
                    $myDateCarbon->addWeekday();
                    $a = in_array($myDateCarbon, $holidays) || $myDateCarbon->isWeekend();
                }  //do not take out holidays and weekends
                else {
                    $myDateCarbon->addDay();
                    $a = false;
                }
            }
        }
        return $myDateCarbon->format(env('Date_Format'));
    }

    /**
     * @param array $data
     * @return string
     */
    public function getLeaveResumeDate(array $data)
    {
        $holidays = $this->getHolidaysArray();
        $endDate = Carbon::parse($data['end_date']);
        $myDateCarbon = Carbon::parse($endDate);

        $a = true;
        while ($a == true) {
            //take out holidays and weekends.
            $myDateCarbon->addWeekday();
            $a = in_array($myDateCarbon, $holidays) || $myDateCarbon->isWeekend();
        }
        return $myDateCarbon->format(env('Date_Format'));
    }

    public function getCreateLeave(array $data = null)
    {
        if(isset($data['leave_id']))
        {
            $leave = $this->findLeaveById($data['leave_id']);
            $employee = $leave->employee;
            $show_emp = false;

        }else{
            $leave = new Leave();
            $employee = $this->employeeRepo->find(user()->id)?? new Employee();
            $show_emp = user()->can('create-leaves');
        }

        $leave_types = $this->getLeaveTypes($employee);
        $annual = $this->getAnnualLeave($employee)??new LeaveBalance();

        return [
            'show_employees' => $show_emp,
            'employee' => $employee,
            'leave' => $leave,
            'annual' => $annual,
            'leave_types' => $leave_types,
        ];
    }

    /**
     * @param mixed $employee
     * @param $leave_type1
     * @param mixed $leaveYear
     * @param Carbon $startOfYear
     * @param Carbon $endOfYear
     * @return void
     */
    private function updateLeaveBalance(mixed $employee, $leave_type1, mixed $leaveYear, Carbon $startOfYear, Carbon $endOfYear): void
    {
        $leave_type = $this->getLeaveType($employee, $leave_type1);
        $total_leave = Leave::where('employee_id', $employee->id)
            ->where('leave_year', $leaveYear)
            ->where('leave_type_id', $leave_type->id)
            ->where('status', 'approved')
            ->sum('total_days');

        $outstanding = $leave_type->allocated_days - $total_leave;

        LeaveBalance::updateOrCreate(
            [
                'leave_year' => $leaveYear,
                'leave_type_id' => $leave_type->id,
                'employee_id' => $employee->id,
            ],
            [
                'allocated_days' => $leave_type->allocated_days,
                'total_days' => $leave_type->allocated_days,
                'spent_days' => $total_leave,
                'start_date' => $startOfYear,
                'end_date' => $endOfYear,
                'leave_category' => $leave_type->leave_category,
                'company_id' => user()->company_id,
                'outstanding_days' => $outstanding,
            ]
        );
    }
}
