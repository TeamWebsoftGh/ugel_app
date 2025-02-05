<?php

namespace App\Services\Interfaces;


use App\Models\Order;

interface IPayStackService
{
    public function getLocalPaymentStatus($applicant);
    public function getRemotePaymentStatus($applicant);

    public function makePayment(Order $order);

    public function CheckPaymentStatus(Order $order);
}
