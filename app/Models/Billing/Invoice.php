<?php

namespace App\Models\Billing;


use App\Abstracts\Model;
use App\Models\Client\Client;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'client_id',
        'total_amount',
        'invoice_date',
        'invoice_number',
        'due_date',
        'sub_total_amount',
        'is_active',
        'created_by',
        'company_id',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class)->withDefault();
    }
}
