<?php

namespace App\Models\Client;

use App\Abstracts\Model;
use Carbon\Carbon;

class ClientType extends Model
{
    protected $tenantable = false;
    protected $fillable = [
        'name',
        'code',
        'category',
        'is_active',
        'company_id',
        'created_by',
    ];
}
