<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Client\Client;

class WorkflowRequest extends Model
{
    protected $fillable = [
        'status',
        'action_type',
        'is_completed',
        'is_active',
        'current_flow_sequence',
        'approved_at',
        'client_id',
        'user_id',
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

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
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

    public function workflow()
    {
        return $this->belongsTo(Workflow::class)->withDefault();
    }
}
