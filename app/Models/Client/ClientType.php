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

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format').' h:mA');
    }
}
