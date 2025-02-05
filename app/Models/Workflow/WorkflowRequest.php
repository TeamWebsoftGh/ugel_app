<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use Carbon\Carbon;

class WorkflowRequest extends Model
{
    protected $fillable = [
        'status',
        'action_type',
        'is_completed',
        'is_active',
        'current_flow_sequence',
        'approved_at',
        'employee_id',
        'created_at',
        'updated_at',
        'workflow_requestable_id',
        'workflow_requestable_type',
        'workflow_type_id',
        'workflow_id',
        'company_id',
    ];

    public function workflow_requestable()
    {
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault();
    }

    public function workflowRequestDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WorkflowRequestDetail::class, 'workflow_request_id');
    }

    public function currentImplementor()
    {
        $wf_req_detail =  $this->workflowRequestDetails()
            ->whereIn('status', ['pending'])
            ->orderByDesc('id')->first();
        if(!isset($wf_req_detail))
        {
            return null;
        }

        return $wf_req_detail->implementor;
    }

    public function workflowType()
    {
        return $this->belongsTo(WorkflowType::class)->withDefault();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'). ' h:i a');
    }
}
