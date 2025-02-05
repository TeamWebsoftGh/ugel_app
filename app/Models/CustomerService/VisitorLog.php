<?php

namespace App\Models\CustomerService;

use App\Abstracts\Model;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorLog extends Model
{
    use HasFactory;

    protected $appends =['full_name'];

    protected $fillable = [
        'company_id',
        'visitor_id',
        'check_out',
        'check_in',
        'first_name',
        'last_name',
        'phone_number',
        'log_date',
        'company',
        'created_from',
        'reason',
        'note',
        'employee_id',
        'created_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault();
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class)->withDefault();
    }

    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->title)).' ' . ucwords(strtolower($this->first_name)) . ' ' . ucwords(strtolower($this->last_name));
    }
}
