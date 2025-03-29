<?php

namespace App\Models\Billing;


use App\Abstracts\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'client_id',
        'total_amount',
        'invoice_number',
        'due_date',
        'sub_total_amount',
        'is_active',
        'created_by',
        'company_id',
        'status'
    ];
}
