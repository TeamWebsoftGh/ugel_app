<?php

namespace App\Traits;

use App\Models\Timesheet\Holiday;
use App\Models\Timesheet\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait LeaveTrait
{
    public function getLeaveType($employee, $leaveTypeId = null)
    {
        $annualLeaveType = LeaveType::firstWhere(['leave_category' => 'annual', 'is_active'=> 1]);
        $leaveType = LeaveType::firstWhere(['id' => $leaveTypeId, 'is_active'=> 1]);

        if($leaveType == null)
        {
            $leaveType = $annualLeaveType;
        }

        if($employee == null)
        {
            return $leaveType;
        }

        $leaveTypeDetail = $leaveType->details()
            ->where(function ($query) use ($employee) {
                $query->where('employee_type_id', $employee->employee_type_id)
                    ->orWhereNull('employee_type_id');

                $query->where('employee_category_id', $employee->employee_category_id)
                    ->orWhereNull('employee_category_id');

                $query->where('designation_id', $employee->designation_id)
                    ->orWhereNull('designation_id');

                $query->where('department_id', $employee->department_id)
                    ->orWhereNull('department_id');

                $query->where('branch_id', $employee->location_id)
                    ->orWhereNull('branch_id');

                $query->where('minimum_service_months', '<=', $employee->ServiceMonths)
                    ->orWhereNull('minimum_service_months');
            })
            ->orderBy('allocated_days', 'desc')
            ->first();

        if ($leaveTypeDetail) {
            $leaveType->allocated_days = $leaveTypeDetail->allocated_days;
            $leaveType->minimum_service_months = $leaveTypeDetail->minimum_service_month;
            $leaveType->pay_percentage = $leaveTypeDetail->pay_percentage;
        }

        if ($employee->leave_days !== null && $leaveType->id === $annualLeaveType?->id) {
            $leaveType->allocated_days = $employee->leave_days;
        }

        return $leaveType;
    }

    public function getLeaveType1($employee, $leaveType = 1)
    {
        $leaveType_ids = [];
        $leave = LeaveType::find($leaveType);
        $leaveTypes = DB::table('leave_type_details')
            ->join('leave_types', 'leave_type_details.leave_type_id', '=', 'leave_types.id')
            ->select('leave_type_details.*', 'leave_types.allocated_days', 'leave_types.minimum_service_month', 'leave_types.leave_type_name')
            ->where('leave_types.id', $leaveType)
            ->where('leave_types.is_active', 1)
            ->get();

        foreach ($leaveTypes as $leaveType)
        {
            if(($leaveType->employee_type_id != null && $leaveType->employee_type_id != $employee->employee_type_id)
                || ($leaveType->employee_category_id != null && $leaveType->employee_category_id != $employee->employee_category_id)
                || ($leaveType->designation_id != null && $leaveType->designation_id != $employee->designation_id)
                || ($leaveType->department_id != null && $leaveType->department_id != $employee->department_id)
                || ($leaveType->location_id != null && $leaveType->location_id != $employee->location_id)
                || ($leaveType->minimum_service_month != null && $leaveType->minimum_service_month > $employee->ServiceMonths)
            )
            {
                $leaveType_ids[] = $leaveType->id;
            }
        }

        $leaveTypes = $leaveTypes->except($leaveType_ids);

        if(count($leaveTypes) > 0)
        {
            $leaveType = $leaveTypes->first();
            $leave->allocated_days = $leaveType->allocated_days;
            $leave->minimum_service_months = $leaveType->minimum_service_months;
            $leave->pay_percentage = $leaveType->pay_percentage;
        }
        if($employee->leave_days != null && $leave->id == 1)
        {
            $leave->allocated_days = $employee->leave_days;
        }

        return $leave;
    }

    public function getLeaveDays($employee, $leaveType = 1)
    {
        $leave = $this->getLeaveType($employee);

        return $leave->allocated_days;
    }

    public function getLeaveTypes($employee)
    {
        $leaveTypes = LeaveType::where('is_active', 1);

        if ($employee !== null) {
            $leaveTypes = $leaveTypes->where('gender', $employee->gender)->orWhere("gender", null)->orWhere('gender', '');
        }

        return $leaveTypes->get()->map(function ($type) use ($employee) {
            return $this->getLeaveType($employee, $type->id);
        });
    }

    /**
     * @return array
     */
    public function getHolidaysArray()
    {
        $hols = Holiday::all()->pluck('holiday_date')->toArray();
        $holidays = array();
        foreach ($hols as $hol) {$holidays[] = Carbon::parse($hol);}
        return $holidays;
    }

    public function getResumeDate($endDate)
    {
        $holidays = $this->getHolidaysArray();
        $endDate = Carbon::parse($endDate);
        $myDateCarbon = Carbon::parse($endDate);

        $a = true;
        while ($a == true) {
            //take out holidays and weekends.
            $myDateCarbon->addWeekday();
            $a = in_array($myDateCarbon, $holidays) || $myDateCarbon->isWeekend();
        }
        return $myDateCarbon->format(env('Date_Format'));
    }

    public function getEndDate(string $startDate, $duration, $leaveTypeId): string
    {
        $startDate = Carbon::parse($startDate);
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

    public function checkForHolidayOrWeekend($startDate)
    {
        $date = Carbon::parse($startDate);
        $hols = Holiday::all()->pluck('start_date')->toArray();
        $holidays = [] ;
        $date_s = $date->toDateString();
        foreach ($hols as $hol) {$holidays[] = Carbon::parse($hol)->toDateString();}
        return (int ) ($date->isWeekend() || in_array($date_s, $holidays)); // Day is a holiday or weekend
    }
}
