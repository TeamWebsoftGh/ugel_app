<?php

namespace App\Models\Property;

use App\Abstracts\Model;

class PropertyType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'property_category_id',
        'short_name'
    ];

    public function propertyCategory()
    {
        return $this->belongsTo(PropertyCategory::class)->withDefault(['name' => 'General']);
    }

}
