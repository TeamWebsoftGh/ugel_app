<?php

namespace App\Models\Billing;

use App\Abstracts\Model;
use App\Models\Client\Client;
use App\Models\Property\Property;
use App\Models\Property\PropertyUnit;
use App\Models\Property\Room;

class Booking extends Model
{
    //
    protected $fillable = [
        'client_id',
        'booking_number',
        'property_id',
        'property_unit_id',
        'room_id',
        'booking_period_id',
        'booking_type',
        'lease_start_date',
        'lease_end_date',
        'extension_date',
        'total_price',
        'sub_total',
        'is_active',
        'total_paid',
        'booking_date',
        'company_id',
        'created_by',
        'rent_type',
        'rent_duration',
        'status'
    ];


    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault();
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class)->withDefault();
    }

    public function room()
    {
        return $this->belongsTo(Room::class)->withDefault();
    }

    public function bookingPeriod()
    {
        return $this->belongsTo(BookingPeriod::class)->withDefault();
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->withDefault();
    }

    public function scopeActive($query)
    {
        return $query->whereDate('lease_start_date', '<=', now())
            ->whereDate('lease_end_date', '>=', now());
    }

}
