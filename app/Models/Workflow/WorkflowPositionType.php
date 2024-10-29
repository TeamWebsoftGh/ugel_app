<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowPositionType extends Model
{
    use HasFactory;

    protected $fillable= [
        'name',
        'code',
        'description',
        'is_active',
        'is_workflow_only',
    ];
}
