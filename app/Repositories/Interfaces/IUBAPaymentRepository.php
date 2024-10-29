<?php

namespace App\Repositories\Interfaces;

use App\Models\Applicant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface IUBAPaymentRepository
{
   // public function processPayment($applicant, $custom_data=null);

    public function processPayment();

    public function checkPaymentStatus();

    public function initPayment(array $data,$service_url);
    public function get($request,$applicant);
    public function paymentStatus($applicant);
    public function statusCheck($applicant);
    public function post( array $data,$applicant);
}
