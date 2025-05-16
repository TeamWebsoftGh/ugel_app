<?php

namespace App\Services\Billing\Interfaces;


use App\Models\Billing\Invoice;
use App\Services\Interfaces\IBaseService;

interface IInvoiceService extends IBaseService
{
    public function listInvoices(array $filters = []);

    public function createInvoice(array $data);

    public function findInvoiceById($id);

    public function updateInvoice(array $data, Invoice $invoice);

    public function deleteInvoice(Invoice $invoice);

    public function deleteMultipleInvoices(array $ids);
}
