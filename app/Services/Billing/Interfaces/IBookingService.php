<?php

namespace App\Services\Billing\Interfaces;


use App\Models\Billing\Booking;
use App\Services\Interfaces\IBaseService;

interface IBookingService extends IBaseService
{
    public function listBookings(array $filters = []);

    public function createBooking(array $data);

    public function findBookingById($id);

    public function updateBooking(array $data, Booking $booking);

    public function deleteBooking(Booking $booking);

    public function deleteMultipleBookings(array $ids);
}
