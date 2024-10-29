<?php

namespace App\Repositories\Interfaces;

use App\Models\CustomerService\Enquiry;
use Illuminate\Support\Collection;

interface IEnquiryRepository extends IBaseRepository
{
    public function updateEnquiry(array $params, Enquiry $enquiry);

    public function listEnquiryMessages(string $order = 'id', string $sort = 'desc') : Collection;

    public function createEnquiry(array $params) : Enquiry;

    public function findEnquiryById(int $id) : Enquiry;
}
