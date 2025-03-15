<?php

namespace App\Models\CustomerService;


use App\Abstracts\Model;

class MaintenanceCategory extends Model
{
    protected $tenantable = false;
    protected $fillable = [
        'name',
        'short_name',
        'is_active',
        'company_id',
        'created_by',
    ];
}
