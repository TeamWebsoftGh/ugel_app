<?php

namespace App\Models\Task;

use App\Abstracts\Model;
use App\Constants\StatusConstants;
use App\Models\Auth\User;
use App\Models\Common\DocumentUpload;
use App\Models\Common\Priority;
use App\Models\Common\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'has_budget',
        'is_ratable',
        'description',
        'parent_task_id',
        'status_id',
        'employee_score',
        'assignee_id',
        'priority_id',
        'user_id',
        'total_weightage',
        'start_date',
        'due_date',
        'resources',
        'budget',
        'resources',
        'revenue_target',
        'actual_revenue',
        'completed_at',
        'stage',
        'remarks',
        'submitted_at',
        'approver_id',
        'company_id',
    ];

    public function priority()
    {
        return $this->belongsTo(Priority::class, "priority_id")->withDefault();
    }

    public function taskStatus()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function CreatedBy()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id')->withTrashed();
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function setStartDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['start_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['start_date'] = null;
        }
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function setDueDateAttribute($value)
    {
        if($value !== null & $value != '')
        {
            $this->attributes['due_date'] = Carbon::createFromFormat(env('Date_Format'), $value)->format('Y-m-d');
        }else{
            $this->attributes['due_date'] = null;
        }
    }

    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function getIsClosedAttribute($value)
    {
        if($this->status_id == StatusConstants::COMPLETED)
            return true;

        if(settings("enforce_due_date", true)){
            $due_date = Carbon::createFromFormat(env('Date_Format'), $this->due_date)->format('Y-m-d');
            if($this->assignee_id == user()->id && $due_date < Carbon::now()->format('Y-m-d'))
                return true;

        }
        return false;
    }

    public function getEditBudgetAttribute()
    {
        if($this->status_id == StatusConstants::PENDING && $this->assignee_id == user()->id && !$this->budget_is_accepted)
        {
            return true;
        }

        return false;
    }

    public function documents()
    {
        return $this->morphMany(DocumentUpload::class, 'documentable');
    }

    public function notifyUsers()
    {
        return $this->belongsToMany(User::class, TaskUser::class);
    }

    public function objectives()
    {
        return $this->hasMany(CheckListItem::class);
    }

    public function scopeDue($query, $date)
    {
        return $query->whereDate('due_date', '=', $date);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status_id', [StatusConstants::SUBMITTED, StatusConstants::COMPLETED, StatusConstants::DECLINED])
            ->where('due_date', '>=', Carbon::now());
    }

    public function getDurationAttribute()
    {
        $deadline = Carbon::createFromFormat(env('Date_Format'), $this->due_date);
        $startDate = Carbon::createFromFormat(env('Date_Format'), $this->start_date);
        $totalDuration = $startDate->diffInHours($deadline);

        return $totalDuration;
    }
}
