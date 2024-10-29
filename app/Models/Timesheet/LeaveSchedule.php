<?php

namespace App\Models\Timesheet;

use App\Models\Employees\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_type_id','company_id','employee_id','start_date','end_date',
        'leave_reason','remarks','status','is_half','is_notify','total_days', 'resumption_date',
        'reliever_id', 'leave_year', 'handover_note','total_days_before','total_days_after'
    ];

    public function LeaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStartDateAttribute($value)
    {
        if(isset($value)){
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }

    public function getEndDateAttribute($value)
    {
        if(isset($value)){
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }

    public function getResumptionDateAttribute($value)
    {
        if(isset($value)){
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'). '-- H:i');
    }
}
