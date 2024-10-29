<?php

namespace App\Models\Employees;

use App\Constants\Constants;
use App\Models\Auth\User;
use App\Models\Timesheet\LeaveBalance;
use App\Models\Timesheet\OfficeShift;
use App\Models\Settings\Country;
use App\Traits\EmployeeRelationTrait;
use App\Traits\LeaveTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, EmployeeRelationTrait, LeaveTrait;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('exit_date', function (Builder $builder) {
//            $builder->where('exit_date', null);
//        });
    }

    protected $appends =['full_name'];

    protected $fillable = [
        'id','first_name','last_name','email','phone_number','alt_phone_number','date_of_birth','gender','employee_type_id',
        'employee_category_id','office_shift_id','other_names','branch_id','designation_id', 'company_id',
        'department_id','is_active', 'remarks', 'tier_2_no', 'tier_3_no', 'maiden_name', 'is_tax_payer',
        'is_ssf_contributor','can_drive', 'has_car', 'has_disability', 'hometown', 'place_of_birth', 'private_email',
        'staff_id','joining_date','exit_date', 'marital_status','address','city','state','country',
        'zip_code','cv','skype_id','fb_id', 'residential_address','postal_address','driver_licence','national_id',
        'twitter_id','linkedIn_id','blogger_id','whatsapp_id','basic_salary','payslip_type','leave_days','end_date','country_id',
        'confirmed_date','probation_end_date','probation_start_date', 'passport_no','status','attendance_type',
        'supervisor_id','religion','ssn','tin','old_staff_id', 'end_date','title', 'is_local',
    ];

    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->title)).' ' . ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name));
    }

    public function getSSNITFullNameAttribute()
    {
        return strtoupper($this->last_name) . ', ' . strtoupper($this->first_name);
    }

    public function supervisor()
    {
        return $this->belongsTo(static::class, 'supervisor_id')->withDefault();
    }

    public function getBirthDateAttribute()
    {
        return $this->date_of_birth;
    }

    public function getServiceMonthsAttribute()
    {
        return Carbon::parse($this->joining_date)->diffInMonths(Carbon::now());
    }

    public function officeShift(){
        return $this->hasOne(OfficeShift::class,'id','office_shift_id')->withDefault();
    }

    public function user(){
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function allowances()
    {
        return $this->hasMany(SalaryAllowance::class);
    }

    public function paySummaries()
    {
        return $this->hasMany(PaySummary::class);
    }

    public function salaryBasic()
    {
        return $this->hasMany(SalaryBasic::class);
    }

    public function deductions()
    {
        return $this->hasMany(SalaryDeduction::class);
    }

    public function commissions()
    {
        return $this->hasMany(SalaryCommission::class);
    }

    public function loans()
    {
        return $this->hasMany(SalaryLoan::class);
    }

    public function otherPayments()
    {
        return $this->hasMany(SalaryOtherPayment::class);
    }

    public function overtimes()
    {
        return $this->hasMany(SalaryOvertime::class);
    }

    public function banks()
    {
        return $this->hasMany(EmployeeBankAccount::class);
    }

    public function getbankAttribute()
    {
        return $this->hasMany(EmployeeBankAccount::class)->firstWhere('is_active', 1)??new EmployeeBankAccount();
    }


    public function employeeAttendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function employeeLeave()
    {
        return $this->hasMany(Leave::class)
            ->select('id','start_date','end_date','status','employee_id')
            ->whereStatus('approved');
    }

    public function setDateOfBirthAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['date_of_birth'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['date_of_birth'] = null;
        }
    }

    public function getDateOfBirthAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function setJoiningDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['joining_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['joining_date'] = null;
        }
    }

    public function getJoiningDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }

    public function setProbationStartDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['probation_start_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['probation_start_date'] = null;
        }
    }

    public function getProbationStartDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }
    public function setProbationEndDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['probation_end_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['probation_end_date'] = null;
        }
    }

    public function getProbationEndDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }

    public function setConfirmedDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['confirmed_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['confirmed_date'] = null;
        }
    }

    public function getConfirmedDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }

    public function setEndDateAttribute($value)
    {
        if($value !== null)
        {
            $this->attributes['end_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['end_date'] = (Carbon::createFromFormat(env('Date_Format'), $this->attributes['date_of_birth'])->addYears(62))->format('Y-m-d');
        }
    }

    public function getEndDateAttribute($value)
    {
        if($value === null)
        {
            if($this->attributes['date_of_birth'] !== null){
                return (Carbon::create($this->attributes['date_of_birth'])->addYears(Constants::RETIREMENT_AGE))->format(env('Date_Format'));
            }
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }

    public function getAnnualLeaveDaysAttribute($value)
    {
        return $this->getLeaveDays($this, 1);
    }

    public function getLeaveBalanceAttribute($value)
    {
        $leave  = LeaveBalance::firstWhere(['employee_id'=>$this->attributes['id'], 'leave_year' => date('Y'), 'leave_type_id'=> 1]);
        $bal = optional($leave)->outstanding_days;

        if($bal){

        }
        return optional($leave)->outstanding_days;
    }

    public function role(){
        return $this->hasOne('Spatie\Permission\Models\Role','id','role_users_id');
    }

    public function empcountry()
    {
        return $this->hasOne(Country::class,'id','country_id')->withDefault();
    }

    public function immigrations()
    {
        return $this->hasMany(EmployeeImmigration::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function qualifications()
    {
        return $this->hasMany(EmployeeQualification::class);
    }

    public function work_experiences()
    {
        return $this->hasMany(EmployeeWorkExperience::class);
    }

    public function emp_status()
    {
        return $this->confirmed_date == null?"On Probation":"Confirmed";
    }

    public function getUserImageAttribute()
    {
        return $this->image??"uploads/user.png";
    }
}
