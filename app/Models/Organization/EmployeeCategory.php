<?php

namespace App\Models\Organization;

use App\Abstracts\Model;
use App\Models\Employees\Employee;

class EmployeeCategory extends Model
{
    protected $fillable = [
        'name',
        'level',
        'probation_duration',
        'no_of_working_days',
        'day_work_hours',
        'tax_category',
        'is_active',
        'company_id',
    ];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
