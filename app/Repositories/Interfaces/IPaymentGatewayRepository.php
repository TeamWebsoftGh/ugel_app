<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment\PaymentGateway;
use Illuminate\Support\Collection;

interface IPaymentGatewayRepository extends IBaseRepository
{
    public function updatePaymentGateway(array $data, PaymentGateway $paymentGateway);

    public function listPaymentGateways(string $order = 'id', string $sort = 'desc') : Collection;

    public function createPaymentGateway(array $data) : PaymentGateway;

    public function findPaymentGatewayById(int $id) : PaymentGateway;

    public function deletePaymentGateway(PaymentGateway $paymentGateway);

}
