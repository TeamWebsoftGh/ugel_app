<?php

namespace App\Models\Timesheet;


use App\Abstracts\Model;

class LeaveType extends Model
{
	protected $fillable = [
		'leave_type_name',
        'allocated_days',
        'can_accumulate',
        'leave_category',
        'calculation_mode',
        'gender',
        'working_days_only',
        'minimum_service_month',
        'pay_percentage',
        'remarks',
        'is_active',
        'company_id',
	];

    public function details()
    {
        return $this->hasMany(LeaveTypeDetail::class);
    }

    public static function forDropdown()
    {
        return LeaveType::where('is_active', 1)
            ->pluck('leave_type_name', 'id');
    }
}
