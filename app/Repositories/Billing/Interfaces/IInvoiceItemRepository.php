<?php

namespace App\Repositories\Billing\Interfaces;

use App\Models\Billing\InvoiceItemLookup;
use App\Repositories\Interfaces\IBaseRepository;

interface IInvoiceItemRepository extends IBaseRepository
{
    public function updateInvoiceItem(array $data, InvoiceItemLookup $invoiceItem);

    public function listInvoiceItems(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createInvoiceItem(array $data) : InvoiceItemLookup;

    public function findInvoiceItemById(int $id) : InvoiceItemLookup;

    public function deleteInvoiceItem(InvoiceItemLookup $invoiceItem);

}
