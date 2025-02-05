<?php

namespace App\Repositories\Interfaces;

use App\Models\ContactUs;
use Illuminate\Support\Collection;

interface IContactUsRepository extends IBaseRepository
{
    public function updateContactUs(array $params, ContactUs $contactUs);

    public function listContactUsMessages(string $order = 'id', string $sort = 'desc') : Collection;

    public function createContactUs(array $params) : ContactUs;

    public function findContactUsById(int $id) : ContactUs;
}
