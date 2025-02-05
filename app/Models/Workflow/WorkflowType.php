<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowType extends Model
{
    use HasFactory;

    protected $fillable= [
        'name',
        'code',
        'description',
        'stages',
        'is_active',
        'subject_type',
        'company_id',
        'sort_order',
        'created_at',
        'updated_at',
    ];
}
