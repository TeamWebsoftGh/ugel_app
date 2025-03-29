<?php

namespace App\Services\Billing;

use App\Models\Billing\InvoiceItemLookup;
use App\Models\Billing\PropertyUnitPrice;
use App\Repositories\Billing\Interfaces\IInvoiceItemRepository;
use App\Services\Billing\Interfaces\IInvoiceItemService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceItemService extends ServiceBase implements IInvoiceItemService
{
    private IInvoiceItemRepository $invoiceItemRepo;

    /**
     * InvoiceItemService constructor.
     * @param IInvoiceItemRepository $invoiceItem
     */
    public function __construct(IInvoiceItemRepository $invoiceItem)
    {
        parent::__construct();
        $this->invoiceItemRepo = $invoiceItem;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return
     */
    public function listInvoiceItems(array $filters =[], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->invoiceItemRepo->listInvoiceItems($filters, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createInvoiceItem(array $data): Response
    {
        $invoiceItem = $this->invoiceItemRepo->createInvoiceItem($data);
        return $this->buildCreateResponse($invoiceItem);
    }


    /**
     * @param array $data
     * @param InvoiceItemLookup $invoiceItem
     * @return Response
     */
    public function updateInvoiceItem(array $data, InvoiceItemLookup $invoiceItem): Response
    {
        //Declaration
        $result = $this->invoiceItemRepo->updateInvoiceItem($data, $invoiceItem);
        return $this->buildUpdateResponse($invoiceItem, $result);
    }

    /**
     * @param int $id
     * @return InvoiceItemLookup|null
     */
    public function findInvoiceItemById($id): ?InvoiceItemLookup
    {
        return $this->invoiceItemRepo->findInvoiceItemById($id);
    }

    /**
     * @param InvoiceItemLookup $invoiceItem
     * @return Response
     */
    public function deleteInvoiceItem(InvoiceItemLookup $invoiceItem)
    {
        //Declaration
        $result = $this->invoiceItemRepo->deleteInvoiceItem($invoiceItem);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleInvoiceItems(array $ids)
    {
        //Declaration
        $result = $this->invoiceItemRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
