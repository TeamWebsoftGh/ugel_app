<?php

namespace App\Models\Property;


use App\Abstracts\Model;

class Room extends Model
{
    protected $fillable = [
        'room_name',
        'floor',
        'block',
        'has_ac',
        'has_washroom',
        'status',
        'is_active',
        'bed_count',
        'description',
        'property_unit_id',
        'company_id',
        'created_by',
        'created_from',
    ];

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class)->withDefault(['name' => 'N/A']);
    }

    public function propertyCategory()
    {
        return $this->hasOneThrough(Property::class, PropertyUnit::class)->withDefault(['name' => 'N/A']);
    }
}
