<?php

namespace App\Models\Billing;

use App\Models\Property\PropertyUnit;
use Illuminate\Database\Eloquent\Model;

class PropertyUnitPrice extends Model
{
    //
    protected $fillable = [
        'property_unit_id',
        'booking_period_id',
        'price',
        'rent_type'
    ];

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class)->withDefault();
    }
}
