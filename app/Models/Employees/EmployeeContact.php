<?php

namespace App\Models\Employees;


use App\Abstracts\Model;

class EmployeeContact extends Model
{
	protected $guarded=[];

	public function employee()
    {
		return $this->belongsTo(Employee::class)->withoutGlobalScope('exit_date');
	}
}
