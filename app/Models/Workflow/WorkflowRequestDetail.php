<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use App\Models\Employees\Employee;
use Carbon\Carbon;

class WorkflowRequestDetail extends Model
{
    protected $fillable = [
        'status',
        'action_status',
        'workflow_request_id',
        'is_active',
        'current_flow_sequence',
        'approved_at',
        'workflow_position_type_id',
        'workflow_type_id',
        'workflow_id',
        'implementor_id',
        'created_at',
        'updated_at',
        'company_id',
        'employee_id',
        'approval_route',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault();
    }

    public function implementor()
    {
        return $this->hasOne(Employee::class,'id', 'implementor_id')->withDefault();
    }

    public function workflowPositionType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WorkflowPositionType::class)->withDefault();
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class)->withDefault();
    }

    public function workflowRequest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WorkflowRequest::class)->withDefault();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'). ' h:i a');
    }

    public function getForwardedAtAttribute($value): string
    {
        return Carbon::parse($value)->format(env('Date_Format'). ' h:i a');
    }

    public function getApprovedAtAttribute($value): string
    {
        return Carbon::parse($value)->format(env('Date_Format'). ' h:i a');
    }
}
