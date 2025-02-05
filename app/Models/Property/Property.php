<?php

namespace App\Models\Property;


use App\Abstracts\Model;

class Property extends Model
{
    protected $fillable = [
        'property_code',
        'property_name',
        'image',
        'icon',
        'number_of_units',
        'status',
        'is_active',
        'description',
        'property_type_id',
        'property_purpose_id'

    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class)->withDefault(['name' => 'N/A']);
    }

    public function propertyPurpose()
    {
        return $this->belongsTo(PropertyPurpose::class)->withDefault(['name' => 'N/A']);
    }

    public function amenities()
    {
        return $this->morphMany(Ameni::class, 'amenitable');
    }

}
