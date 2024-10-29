<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveScheduler extends Model
{
    use HasFactory;

    protected $table = "leave_scheduler";

    protected $fillable = [
        'leave_type_id','company_id','department_id','employee_id','start_date','end_date',
        'leave_reason','remarks','status','is_half','is_notify','total_days', 'resumption_date',
        'reliever_id', 'leave_year', 'handover_note'
    ];

    public function company(){
        return $this->hasOne('App\Models\Company','id','company_id');
    }

    public function department(){
        return $this->hasOne('App\Models\Department','id','department_id');
    }

    public function LeaveType(){
        return $this->hasOne(LeaveType::class,'id','leave_type_id');
    }

    public function employee(){
        return $this->hasOne('App\Models\Employee','id','employee_id')->withoutGlobalScope('exit_date');
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
    }

    public function getStartDateAttribute($value)
    {
        if(isset($value)){
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        if(isset($value)){
            return Carbon::parse($value)->format(env('Date_Format'));
        }else{
            return null;
        }
    }

    public function setResumptionDateAttribute($value)
    {
        $this->attributes['resumption_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
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
