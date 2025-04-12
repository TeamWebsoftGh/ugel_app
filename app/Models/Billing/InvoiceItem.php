<?php

namespace App\Models\Billing;


use App\Abstracts\Model;

class InvoiceItem extends Model
{
    //
    protected $fillable = [
        'invoice_item_lookup_id',
        'description',
        'amount',
        'quantity',
    ];

}
