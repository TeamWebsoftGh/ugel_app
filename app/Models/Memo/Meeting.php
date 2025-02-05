<?php

namespace App\Models\Memo;

use App\Abstracts\Model;
use App\Models\Employees\Employee;
use Carbon\Carbon;

class Meeting extends Model
{
	protected $fillable = [
		'meeting_title',
        'company_id',
        'meeting_note',
        'meeting_date',
        'meeting_time',
		'status',
        'is_notify',
        'department_id',
        'branch_id',
        'subsidiary_id',
	];

	public function employees()
    {
		return $this->belongsToMany(Employee::class);
	}

	public function setMeetingDateAttribute($value)
	{
		$this->attributes['meeting_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getMeetingDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}

}
