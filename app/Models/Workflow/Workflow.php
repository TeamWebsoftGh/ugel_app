<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_position_type_id',
        'workflow_name',
        'status_name',
        'is_active',
        'action',
        'action_type',
        'workflow_type_id',
        'flow_sequence',
        'company_id',
        'created_by',
        'description',
        'submit_message',
        'approve_message',
        'decline_message',
        'forward_message',
        'inform_message',
        'can_update',
        'requires_comment',
    ];

    public function workflowPositionType()
    {
        return $this->belongsTo(WorkflowPositionType::class)->withDefault();
    }

    public function workflowType()
    {
        return $this->belongsTo(WorkflowType::class)->withDefault();
    }

    public function workflowRequests()
    {
        return $this->hasMany(WorkflowRequest::class);
    }
}
