<?php

namespace App\Services\Interfaces;

use App\Models\Order;
use App\Models\Payment;

interface IPaymentService extends IBaseService
{
    public function listPayments();

    public function listPaymentsByCustomer();

    public function createPayment(array $data, Order $order);

    public function changePaymentStatus(Order $order);

    public function findPaymentById(int $id);

    public function findPayment(array $where);

    public function updatePayment(array $params, Payment $payment);

    public function deletePayment(int $id);
}
