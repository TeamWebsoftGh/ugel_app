<?php

namespace App\Models\Property;


use App\Abstracts\Model;
use App\Models\Billing\Booking;
use App\Models\Settings\City;

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
        'city_id',
        'physical_address',
        'description',
        'property_type_id',
        'property_purpose_id'

    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class)->withDefault(['name' => 'N/A']);
    }

    public function propertyCategory()
    {
        return $this->hasOneThrough(PropertyCategory::class, PropertyType::class)->withDefault(['name' => 'N/A']);
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault(['name' => 'N/A']);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, PropertyUnit::class);
    }

    public function propertyPurpose()
    {
        return $this->belongsTo(PropertyPurpose::class)->withDefault(['name' => 'N/A']);
    }

    public function propertyUnits()
    {
        return $this->hasMany(PropertyUnit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function amenities(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Amenity::class, 'amenitable');
    }

    public function getCoverImageAttribute()
    {
        return $this->image ?? "assets/images/user.png";
    }

    public function getNameAttribute()
    {
        return $this->attributes['property_name'];
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
