<?php

namespace App\Models\Timesheet;

use App\Traits\EmployeeRelationTrait;
use Illuminate\Database\Eloquent\Model;

class LeaveTypeDetail extends Model
{
    use EmployeeRelationTrait;

    protected $fillable = [
        'minimum_service_months',
        'allocated_days',
        'remarks',
        'company_id',
        'is_active',
        'leave_type_id',
        'employee_type_id',
        'grade_id',
        'grade_step_id',
        'employee_category_id',
        'designation_id',
        'department_id',
        'location_id',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class)->withDefault();
    }


}
