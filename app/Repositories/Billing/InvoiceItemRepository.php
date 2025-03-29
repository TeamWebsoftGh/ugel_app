<?php

namespace App\Repositories\Billing;

use App\Models\Billing\InvoiceItemLookup;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\IInvoiceItemRepository;

class InvoiceItemRepository extends BaseRepository implements IInvoiceItemRepository
{
    /**
     * InvoiceItemRepository constructor.
     *
     * @param InvoiceItemLookup $invoiceItem
     */
    public function __construct(InvoiceItemLookup $invoiceItem)
    {
        parent::__construct($invoiceItem);
        $this->model = $invoiceItem;
    }

    /**
     * List all the Amenities
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $amenities
     */
    public function listInvoiceItems(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $result = $this->getFilteredList();
        return $result->orderBy($order, $sort);
    }

    /**
     * Create the InvoiceItem
     *
     * @param array $data
     *
     * @return InvoiceItemLookup
     */
    public function createInvoiceItem(array $data): InvoiceItemLookup
    {
        return $this->create($data);
    }

    /**
     * Find the InvoiceItem by id
     *
     * @param int $id
     *
     * @return InvoiceItemLookup
     */
    public function findInvoiceItemById(int $id): InvoiceItemLookup
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update InvoiceItem
     *
     * @param array $params
     *
     * @param InvoiceItemLookup $invoiceItem
     * @return bool
     */
    public function updateInvoiceItem(array $data, InvoiceItemLookup $invoiceItem): bool
    {
        return $this->update($data, $invoiceItem->id);
    }

    /**
     * @param InvoiceItemLookup $invoiceItem
     * @return bool|null
     * @throws \Exception
     */
    public function deleteInvoiceItem(InvoiceItemLookup $invoiceItem)
    {
        return $this->delete($invoiceItem->id);
    }
}
