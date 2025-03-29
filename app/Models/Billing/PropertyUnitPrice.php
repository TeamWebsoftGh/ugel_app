<?php

namespace App\Models\Billing;

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
}
