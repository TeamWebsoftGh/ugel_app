<?php

namespace App\Repositories\Interfaces;

use App\Models\Billing\Payment;

interface IPaymentRepository extends IBaseRepository
{
    public function listPayments(array $filter = [], string $orderBy= 'id', string $sort = 'desc');

    public function checkPaymentStatus();
}
