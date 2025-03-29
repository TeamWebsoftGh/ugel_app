<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'client_id',
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
        'total_paid',
        'company_id',
        'created_by',
        'status'
    ];

}
