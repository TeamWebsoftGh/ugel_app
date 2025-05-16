<?php

namespace App\Repositories\CustomerService\Interfaces;

use App\Models\CustomerService\Enquiry;
use App\Repositories\Interfaces\IBaseRepository;

interface IEnquiryRepository extends IBaseRepository
{
    public function updateEnquiry(array $params, Enquiry $enquiry);

    public function listEnquiryMessages(array $filter= [], string $order = 'id', string $sort = 'desc');

    public function createEnquiry(array $params) : Enquiry;

    public function findEnquiryById(int $id) : Enquiry;
}
