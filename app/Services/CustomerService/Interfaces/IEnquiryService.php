<?php

namespace App\Services\CustomerService\Interfaces;

use App\Models\CustomerService\Enquiry;
use App\Services\Interfaces\IBaseService;

interface IEnquiryService extends IBaseService
{
    public function listEnquiryMessages(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createEnquiry(array $params);

    public function findEnquiryById(int $id) : Enquiry;

    public function updateEnquiry(array $params, Enquiry $enquiry);

    public function changeStatus(bool $status, Enquiry $enquiry);

    public function deleteEnquiry(Enquiry $enquiry);
}
