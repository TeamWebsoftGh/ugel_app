<?php

namespace App\Models\Billing;


use App\Abstracts\Model;

class BookingPeriod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'booking_start_date',
        'booking_end_date',
        'extension_date',
        'lease_start_date',
        'lease_end_date',
        'company_id',
        'created_by',
        'is_active',
    ];

    public function propertyUnitPrices()
    {
        return $this->hasMany(PropertyUnitPrice::class);
    }
}
