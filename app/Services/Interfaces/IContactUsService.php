<?php

namespace App\Services\Interfaces;

use App\Models\ContactUs;
use Illuminate\Support\Collection;

interface IContactUsService extends IBaseService
{
    public function listContactUsMessages(string $order = 'id', string $sort = 'desc'): Collection;

    public function createContactUs(array $params);

    public function findContactUsById(int $id) : ContactUs;

    public function updateContactUs(array $params, ContactUs $contactUs);

    public function changeStatus(bool $status, ContactUs $contactUs);

    public function deleteContactUs(ContactUs $contactUs);
}
