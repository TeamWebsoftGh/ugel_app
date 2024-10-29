<?php

namespace App\Services\Interfaces;

use App\Models\Memo\Contact;
use Illuminate\Support\Collection;

interface IContactService extends IBaseService
{
    public function listContacts(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createContact(array $params);

    public function findContactById(int $id) : Contact;

    public function updateContact(array $params, Contact $contact);

    public function deleteContact(Contact $contact);
}
