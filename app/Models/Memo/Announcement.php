<?php

namespace App\Models\Memo;

use App\Abstracts\Model;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Organization\Subsidiary;
use Carbon\Carbon;

class Announcement extends Model
{
	protected $fillable = [
		'title',
        'start_date',
        'end_date',
        'summary',
        'description',
        'is_notify',
        'company_id',
        'department_id',
        'subsidiary_id',
        'branch_id',
        'is_active',
	];

	public function department()
    {
		return $this->belongsTo(Department::class)->withDefault();
	}

    public function branch()
    {
        return $this->belongsTo(Branch::class)->withDefault();
    }

    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class)->withDefault(['name' => 'N/A']);
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
