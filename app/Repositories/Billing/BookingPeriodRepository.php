<?php

namespace App\Repositories\Billing;

use App\Models\Billing\BookingPeriod;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\IBookingPeriodRepository;

class BookingPeriodRepository extends BaseRepository implements IBookingPeriodRepository
{
    /**
     * BookingPeriodRepository constructor.
     *
     * @param BookingPeriod $bookingPeriod
     */
    public function __construct(BookingPeriod $bookingPeriod)
    {
        parent::__construct($bookingPeriod);
        $this->model = $bookingPeriod;
    }

    /**
     * List all the Amenities
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $amenities
     */
    public function listBookingPeriods(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $result = $this->getFilteredList();
        return $result->orderBy($order, $sort);
    }

    /**
     * Create the BookingPeriod
     *
     * @param array $data
     *
     * @return BookingPeriod
     */
    public function createBookingPeriod(array $data): BookingPeriod
    {
        return $this->create($data);
    }

    /**
     * Find the BookingPeriod by id
     *
     * @param int $id
     *
     * @return BookingPeriod
     */
    public function findBookingPeriodById(int $id): BookingPeriod
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update BookingPeriod
     *
     * @param array $params
     *
     * @param BookingPeriod $bookingPeriod
     * @return bool
     */
    public function updateBookingPeriod(array $data, BookingPeriod $bookingPeriod): bool
    {
        return $this->update($data, $bookingPeriod->id);
    }

    /**
     * @param BookingPeriod $bookingPeriod
     * @return bool|null
     * @throws \Exception
     */
    public function deleteBookingPeriod(BookingPeriod $bookingPeriod)
    {
        return $this->delete($bookingPeriod->id);
    }
}
