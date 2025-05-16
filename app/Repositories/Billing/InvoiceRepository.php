<?php

namespace App\Repositories\Billing;

use App\Models\Billing\Invoice;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\IInvoiceRepository;

class InvoiceRepository extends BaseRepository implements IInvoiceRepository
{
    /**
     * InvoiceRepository constructor.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
    }

    /**
     * List all the Amenities
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $amenities
     */
    public function listInvoices(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->getFilteredList();
        // Join the bookings table if filtering by property unit
        if (!empty($filter['filter_property_unit'])) {
            $query->whereHas('booking.property', function ($q) use ($filter) {
                $q->where('property_unit_id', $filter['filter_property_unit']);
            });
        }
        $query->when(!empty($filter['filter_client']), function ($q) use ($filter) {
            $q->where('client_id', $filter['filter_client']);
        });

        $query->when(!empty($filter['filter_booking']), function ($q) use ($filter) {
            $q->where('booking_id', $filter['filter_booking']);
        });
        return $query->orderBy($order, $sort);
    }
}
