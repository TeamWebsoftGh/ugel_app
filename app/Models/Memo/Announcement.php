<?php

namespace App\Models\Memo;

use App\Abstracts\Model;
use App\Models\Delegate\Constituency;
use App\Models\Delegate\ElectoralArea;
use App\Models\Delegate\PollingStation;
use Carbon\Carbon;

class Announcement extends Model
{
	protected $fillable = [
		'title',
        'start_date',
        'end_date',
        'short_message',
        'message',
        'is_notify',
        'electoral_area_id',
        'constituency_id',
        'polling_station_id',
        'is_sent',
        'created_by',
        'created_from',
        'is_active',
        'type',
        'company_id',
        'file',
        'file_type',
        'tem_type',
        'gender',
        'min_age',
        'max_age',
	];

	public function constituencies()
    {
		return $this->belongsTo(Constituency::class)->withDefault();
	}

    public function electoral_area()
    {
        return $this->belongsTo(ElectoralArea::class)->withDefault();
    }

    public function polling_station()
    {
        return $this->belongsTo(PollingStation::class)->withDefault();
    }

	public function setStartDateAttribute($value)
	{
		$this->attributes['start_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getStartDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}

	public function setEndDateAttribute($value)
	{
		$this->attributes['end_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
	}

	public function getEndDateAttribute($value)
	{
		return Carbon::parse($value)->format(env('Date_Format'));
	}
}
