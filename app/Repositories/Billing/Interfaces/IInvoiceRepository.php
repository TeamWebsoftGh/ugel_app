<?php

namespace App\Repositories\Billing\Interfaces;

use App\Models\Billing\Invoice;
use App\Repositories\Interfaces\IBaseRepository;

interface IInvoiceRepository extends IBaseRepository
{
    public function listInvoices(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
