<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
class WorkflowPositionType extends Model
{
    protected $fillable= [
        'name',
        'code',
        'description',
        'is_active',
        'is_workflow_only',
    ];
}
