<?php

namespace App\Models\Timesheet;

use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = [
        'leave_type_id','company_id','employee_id','start_date','end_date',
        'allocated_days','remarks','status','leave_year','leave_category',
        'total_days',
        'outstanding_days',
        'spent_days',
        'deferred_days',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withoutGlobalScope('exit_date');
    }
}
