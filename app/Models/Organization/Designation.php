<?php

namespace App\Models\Organization;

use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation_name',
        'company_id',
        'department_id',
        'is_active',
        'is_workflow',
        'enforce_max_staff_count',
        'designation_type',
        'max_staff_count',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
