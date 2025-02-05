<?php

namespace App\Models\Organization;

use App\Abstracts\Model;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'status',
        'slug',
        'phone_number',
        'email_address',
        'digital_address',
        'city',
        'description',
        'fax_number',
        'region_id',
        'cover_image'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getCoverImageAttribute()
    {
        return $this->image??"department.png";
    }
}
