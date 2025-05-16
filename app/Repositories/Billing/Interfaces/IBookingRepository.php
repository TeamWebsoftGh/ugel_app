<?php

namespace App\Repositories\Billing\Interfaces;

use App\Models\Billing\Booking;
use App\Repositories\Interfaces\IBaseRepository;

interface IBookingRepository extends IBaseRepository
{
    public function updateBooking(array $data, Booking $booking);

    public function listBookings(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createBooking(array $data) : Booking;

    public function findBookingById(int $id) : Booking;

    public function deleteBooking(Booking $booking);

}
