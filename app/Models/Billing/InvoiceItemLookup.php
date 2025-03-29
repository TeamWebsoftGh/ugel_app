<?php

namespace App\Models\Billing;


use App\Abstracts\Model;

class InvoiceItemLookup extends Model
{
    //
    protected $fillable = [
        'name',
        'is_active',
        'description',
        'price',
        'company_id',
        'created_by'
    ];
}
