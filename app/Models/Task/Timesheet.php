<?php

namespace App\Models\Task;

use App\Abstracts\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'task_id',
        'expense',
        'revenue',
        'challenges',
        'end_time',
        'duration',
        'comments',
        'note',
        'check_list_item_id',
        'company_id',
        'user_id',
        'status',
    ];

    public function getStartDateAttribute()
    {
        return Carbon::parse($this->start_time)->format(env('Date_Format')." h:i a");
    }

    public function getEndDateAttribute()
    {
        return Carbon::parse($this->end_time)->format(env('Date_Format')." h:i a");
    }

    public function getActivityStatusAttribute()
    {
        return ucfirst($this->status);
    }

    public function getDurationAttribute($value)
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        $days = $end->diffInDays($start);
        $hours = ($end->diffInHours($start))%24;
        $minutes = ($end->diffInMinutes($start))%60;
        $seconds = ($end->diffInSeconds($start))%60;

        return $days.' day(s) '.$hours.' hour(s) ' . $minutes. ' minute(s)';
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function objective()
    {
        return $this->belongsTo(CheckListItem::class)->withDefault(['name' => $this->task->title]);
    }
}
