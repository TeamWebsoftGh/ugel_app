<?php

namespace App\Models\Payment;


use App\Abstracts\Model;

class PaymentGateway extends Model
{
    //
    protected $casts = [
    'settings' => 'object',
    ];

    protected $fillable = [
        'slug',
        'name',
        'description',
        'instruction',
        'settings',
        'status'
    ];
}
