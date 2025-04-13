<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;

class WorkflowType extends Model
{
    protected $fillable= [
        'name',
        'code',
        'description',
        'stages',
        'is_active',
        'subject_type',
        'company_id',
        'sort_order',
        'approval_route'
    ];
}
