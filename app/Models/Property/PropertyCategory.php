<?php

namespace App\Models\Property;

use App\Abstracts\Model;

class PropertyCategory extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'description',
        'short_name',
        'company_id'
    ];
}
