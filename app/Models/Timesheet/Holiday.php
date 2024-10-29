<?php

namespace App\Models\Timesheet;

use App\Abstracts\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Holiday extends Model
{
	protected $fillable = [
		'event_name',
        'description',
        'start_date',
        'end_date',
        'company_id',
        'is_publish'
	];

    public static function getHolidayByDates($startDate, $endDate)
    {
        if (!is_null($endDate)||!is_null($startDate)) {
            return Holiday::select(DB::raw('DATE_FORMAT(start_date, "%Y-%m-%d") as holiday_date'), 'event_name')->where('date', '>=', $startDate)->where('start_date', '<=', $endDate)->get();
        }
        return null;
    }

    public static function checkHolidayByDate($date){
        return Holiday::Where('start_date', $date)->first();
    }

    public function getStartDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }

    public function getEndDateAttribute($value)
    {
        if($value === null)
        {
            return '';
        }
        else{
            return Carbon::parse($value)->format(env('Date_Format'));
        }
    }
}
