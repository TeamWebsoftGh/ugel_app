<?php

namespace App\Models\Workflow;

use App\Abstracts\Model;
use App\Models\Auth\User;
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
        'company_id',
        'created_by',
        'comment',
        'description',
        'import_id',
        'reports_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function workflowPositionType()
    {
        return $this->belongsTo(WorkflowPositionType::class)->withDefault();
    }

    public function getSubjectAttribute()
    {
        if(isset($this->subject_type))
        {
            $subject =$this->attributes['subject_type']::find($this->attributes['subject_id']);
            return $subject;
        }
    }
}
