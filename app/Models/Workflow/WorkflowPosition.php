<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_position_type_id',
        'position_name',
        'is_active',
        'subject_id',
        'subject_type',
        'user_id',
        'description',
        'short_name',
        'comment',
        'description',
        'import_id',
        'reports_to',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault();
    }

    public function workflowPositionType()
    {
        return $this->belongsTo(WorkflowPositionType::class)->withDefault();
    }
}
