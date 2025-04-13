<?php

namespace App\Services\Billing;

use App\Models\Billing\Invoice;
use App\Models\Billing\PropertyUnitPrice;
use App\Repositories\Billing\Interfaces\IInvoiceRepository;
use App\Services\Billing\Interfaces\IInvoiceService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceService extends ServiceBase implements IInvoiceService
{
    private IInvoiceRepository $invoiceRepo;

    /**
     * InvoiceService constructor.
     * @param IInvoiceRepository $invoice
     */
    public function __construct(IInvoiceRepository $invoice)
    {
        parent::__construct();
        $this->invoiceRepo = $invoice;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return
     */
    public function listInvoices(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->invoiceRepo->listInvoices($filters, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createInvoice(array $data): Response
    {
        $invoice = $this->invoiceRepo->create($data);
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'invoice_item_lookup_id' => $item['lookup_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }
        return $this->buildCreateResponse($invoice);
    }


    /**
     * @param array $data
     * @param Invoice $invoice
     * @return Response
     */
    public function updateInvoice(array $data, Invoice $invoice): Response
    {
        // Compute total from items
        $total = 0;
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $quantity = $item['quantity'] ?? 1;
                $amount = $item['amount'] ?? 0;
                $total +=$amount;
            }
        }

        $data['total_amount'] = $total+$invoice->sub_total_amount; // set total before update
        $result = $this->invoiceRepo->update($data, $invoice->id);

        // Refresh invoice instance in case relationships are cached
        $invoice->refresh();

        // Replace items
        $invoice->items()->delete();

        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'invoice_item_lookup_id' => $item['lookup_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'amount' => $item['amount'] ?? 0,
                ]);
            }
        }

        return $this->buildUpdateResponse($invoice, $result);
    }


    /**
     * @param int $id
     * @return Invoice|null
     */
    public function findInvoiceById($id): ?Invoice
    {
        return $this->invoiceRepo->findOneOrFail($id);
    }

    /**
     * @param Invoice $invoice
     * @return Response
     */
    public function deleteInvoice(Invoice $invoice)
    {
        //Declaration
        $result = $this->invoiceRepo->delete($invoice->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleInvoices(array $ids)
    {
        //Declaration
        $result = $this->invoiceRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
