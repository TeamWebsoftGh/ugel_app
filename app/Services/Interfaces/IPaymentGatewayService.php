<?php

namespace App\Services\Interfaces;

use App\Models\Payment\PaymentGateway;
use Illuminate\Support\Collection;

interface IPaymentGatewayService extends IBaseService
{
    public function listAllPaymentGateways(string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function listOnlinePaymentGateways(string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function listOfflinePaymentGateways(string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createPaymentGateway(array $params);

    public function findPaymentGatewayById(int $id) : PaymentGateway;

    public function findPaymentGatewayBySlug(string $slug): PaymentGateway;

    public function updatePaymentGateway(array $params, PaymentGateway $PaymentGateway);

    public function deletePaymentGateway(PaymentGateway $paymentGateway);
}
