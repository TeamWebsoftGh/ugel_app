<?php

namespace App\Models\Billing;


use App\Abstracts\Model;
use App\Models\Client\Client;
use App\Models\Payment\PaymentGateway;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'client_id',
        'amount',
        'payment_gateway_id',
        'transaction_id',
        'payment_date',
        'account_number',
        'account_type',
        'merchant_name',
        'reference_id',
        'reference_id',
        'account_name',
        'description',
        'status',
        'created_by',
        'created_from',
        'payment_method',
        'charge',
        'company_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class)->withDefault();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->withDefault();
    }

}
