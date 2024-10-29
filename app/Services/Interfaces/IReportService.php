<?php

namespace App\Services\Interfaces;

use App\Enums\ApplicationType;
use App\Models\Applicant;
use App\Models\ApplicationTicket;
use App\Models\Division;
use App\Models\Payment;
use App\Models\Resource\Category;

interface IReportService extends IBaseService
{
    public function listBookingByCategory(Category $division);

    public function listBookingByStaff();

    public function statBooking(array $param);

    public function prepareReport(array $param);

    public function statBookingByStaff(array $data);

    public function statBookingByProgram(array $data);

    public function findPaymentById(int $id);

    public function findPayment(array $where);

    public function updatePayment(array $params, Payment $payment);
}
