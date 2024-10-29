<?php

namespace App\Models\Employees;

use App\Abstracts\Model;
use Carbon\Carbon;

class EmployeeWorkExperience extends Model
{
	protected $fillable=[
        'company_id',
        'status',
        'start_date',
        'end_date',
        'job_title',
        'company_name',
        'description',
        'employee_id',
    ];

	protected $table ='employee_work_experience';

	public function employee()
    {
		return $this->belongsTo(Employee::class);
	}

}
