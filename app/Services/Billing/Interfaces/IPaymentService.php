<?php

namespace App\Services\Billing\Interfaces;


use App\Models\Billing\Invoice;
use App\Models\Billing\Payment;
use App\Services\Interfaces\IBaseService;

interface IPaymentService extends IBaseService
{
    public function listPayments(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc');
    public function listPaymentsByCustomer();

    public function createPayment(array $data, Invoice $invoice);

    public function changeStatus(Payment $payment, $status);

    public function findPayment(array $where);

    public function updatePayment(array $data, Payment $payment);

}
