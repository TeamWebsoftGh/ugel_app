<?php

namespace App\Services\Billing\Interfaces;


use App\Models\Billing\BookingPeriod;
use App\Services\Interfaces\IBaseService;

interface IBookingPeriodService extends IBaseService
{
    public function listBookingPeriods(array $filters = []);

    public function createBookingPeriod(array $data);

    public function findBookingPeriodById($id);

    public function updateBookingPeriod(array $data, BookingPeriod $bookingPeriod);

    public function deleteBookingPeriod(BookingPeriod $bookingPeriod);

    public function deleteMultipleBookingPeriods(array $ids);
}
