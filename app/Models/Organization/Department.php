<?php

namespace App\Models\Organization;

use App\Abstracts\Model;
use App\Models\Employees\Employee;

class Department extends Model
{
	protected $fillable = [
		'department_name',
        'company_id',
        'department_head','
        is_active',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

	public function DepartmentHead()
    {
		return $this->belongsTo(Employee::class,'department_head');
	}
}
