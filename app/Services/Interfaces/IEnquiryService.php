<?php

namespace App\Services\Interfaces;

use App\Models\CustomerService\Enquiry;
use Illuminate\Support\Collection;

interface IEnquiryService extends IBaseService
{
    public function listEnquiryMessages(string $order = 'id', string $sort = 'desc'): Collection;

    public function createEnquiry(array $params);

    public function findEnquiryById(int $id) : Enquiry;

    public function updateEnquiry(array $params, Enquiry $enquiry);

    public function changeStatus(bool $status, Enquiry $enquiry);

    public function deleteEnquiry(Enquiry $enquiry);
}
