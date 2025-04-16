<?php

namespace App\Models\Property;

use App\Abstracts\Model;

class Amenity extends Model
{
    protected $fillable  = [
        'name',
        'is_active',
        'created_by',
        'created_from',
        'short_name',
        'company_id'
    ];

    public function amenitable()
    {
        return $this->morphTo();
    }
}
