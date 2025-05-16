<?php

namespace App\Repositories\Interfaces;

use App\Models\Communication\Contact;
use Illuminate\Support\Collection;

interface IContactRepository extends IBaseRepository
{
    public function updateContact(array $params, Contact $contact);

    public function listContacts(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createContact(array $params) : Contact;

    public function findContactById(int $id) : Contact;
}
