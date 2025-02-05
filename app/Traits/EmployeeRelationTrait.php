<?php

namespace App\Traits;

use App\Models\Common\Company;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Organization\Designation;
use App\Models\Organization\EmployeeCategory;
use App\Models\Organization\EmployeeType;
use App\Models\Organization\Grade;
use App\Models\Organization\GradeStep;

trait EmployeeRelationTrait
{
    public function employeeCategory()
    {
        return $this->belongsTo(EmployeeCategory::class)->withDefault();
    }

    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id')->withDefault();
    }

    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id')->withDefault();
    }

    public function location()
    {
        return $this->belongsTo(Branch::class)->withDefault();
    }

    public function branch()
    {
        return $this->hasOne(Branch::class,'id','location_id')->withDefault();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class)->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class)->withDefault();
    }

    public function gradeStep()
    {
        return $this->belongsTo(GradeStep::class)->withDefault();
    }

    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }
}
