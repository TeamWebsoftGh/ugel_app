<?php

namespace App\Services\Billing\Interfaces;


use App\Models\Billing\InvoiceItemLookup;
use App\Services\Interfaces\IBaseService;

interface IInvoiceItemService extends IBaseService
{
    public function listInvoiceItems(array $filters = []);

    public function createInvoiceItem(array $data);

    public function findInvoiceItemById($id);

    public function updateInvoiceItem(array $data, InvoiceItemLookup $invoiceItem);

    public function deleteInvoiceItem(InvoiceItemLookup $invoiceItem);

    public function deleteMultipleInvoiceItems(array $ids);
}
