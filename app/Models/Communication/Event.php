<?php

namespace App\Models\Communication;

use App\Abstracts\Model;
use Carbon\Carbon;

class Event extends Model
{
	protected $fillable = [
		'event_title',
        'company_id',
        'department_id',
        'branch_id',
        'subsidiary_id',
        'event_note',
        'event_date',
        'event_time',
		'status',
        'is_notify'
	];

	public function department(){
		return $this->hasOne('App\Models\Department','id','department_id');
	}

	public function setEventDateAttribute($value)
	{
		$this->attributes['event_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getEventDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}

}
