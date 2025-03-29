<?php

namespace App\Repositories\Billing;

use App\Models\Billing\Booking;
use App\Repositories\BaseRepository;
use App\Repositories\Billing\Interfaces\IBookingRepository;

class BookingRepository extends BaseRepository implements IBookingRepository
{
    /**
     * BookingRepository constructor.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        parent::__construct($booking);
        $this->model = $booking;
    }

    /**
     * List all the Amenities
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $amenities
     */
    public function listBookings(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $result = $this->getFilteredList();
        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Booking
     *
     * @param array $data
     *
     * @return Booking
     */
    public function createBooking(array $data): Booking
    {
        return $this->create($data);
    }

    /**
     * Find the Booking by id
     *
     * @param int $id
     *
     * @return Booking
     */
    public function findBookingById(int $id): Booking
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Booking
     *
     * @param array $params
     *
     * @param Booking $booking
     * @return bool
     */
    public function updateBooking(array $data, Booking $booking): bool
    {
        return $this->update($data, $booking->id);
    }

    /**
     * @param Booking $booking
     * @return bool|null
     * @throws \Exception
     */
    public function deleteBooking(Booking $booking)
    {
        return $this->delete($booking->id);
    }
}
