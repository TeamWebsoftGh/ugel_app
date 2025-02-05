<?php

namespace App\Services\Interfaces;


use App\Models\Order;

interface ISmsService
{
    public function sendSmsViaTxtConnect($message, $phoneNumber, $subject= "Eziwrite");
    public function getRemotePaymentStatus($applicant);

    public function makePayment(Order $order);

    public function CheckPaymentStatus(Order $order);
}
