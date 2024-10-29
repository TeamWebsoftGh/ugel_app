<?php

namespace App\Models\Timesheet;

use App\Abstracts\Model;
use Carbon\Carbon;

class Attendance extends Model
{

	protected $guarded = [];

	public $timestamps = false;


	public function employee(){
		return $this->belongsTo('Employee::class')->withoutGlobalScope('exit_date');
	}

	public function setAttendanceDateAttribute($value)
	{
		$this->attributes['attendance_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getAttendanceDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}
}
