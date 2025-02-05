<?php

namespace App\Repositories\Interfaces;

use App\Models\Applicant;
use App\Models\Division;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface IPaymentRepository extends IBaseRepository
{
   // public function processPayment($applicant, $custom_data=null);

    public function listPayments(string $order = 'id', string $sort = 'desc');

    public function checkPaymentStatus();

    public function createPayment(array $data, $applicant) : Payment;
}
