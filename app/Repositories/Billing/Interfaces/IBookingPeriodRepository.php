<?php

namespace App\Repositories\Billing\Interfaces;

use App\Models\Billing\BookingPeriod;
use App\Repositories\Interfaces\IBaseRepository;

interface IBookingPeriodRepository extends IBaseRepository
{
    public function updateBookingPeriod(array $data, BookingPeriod $bookingPeriod);

    public function listBookingPeriods(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createBookingPeriod(array $data) : BookingPeriod;

    public function findBookingPeriodById(int $id) : BookingPeriod;

    public function deleteBookingPeriod(BookingPeriod $bookingPeriod);

}
