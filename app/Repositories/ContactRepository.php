<?php

namespace App\Repositories;

use App\Models\Memo\Contact;
use App\Repositories\Interfaces\IContactRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ContactRepository extends BaseRepository implements IContactRepository
{
    Public function __construct(Contact $contact)
    {
        parent::__construct($contact);
        $this->model = $contact;
    }

    public function listContacts(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        if (!empty($filter['filter_contact_group']))
        {
            $result = $result->where('contact_group_id', $filter['filter_contact_group']);
        }
        return $result->orderBy($order, $sort)->get();
    }

    /**
     * @param int $id
     * @return Contact
     * @throws ModelNotFoundException
     */
    public function findContactById(int $id) : Contact
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Contact
     */
    public function createContact(array $params) : Contact
    {
        $contact = new Contact($params);
        $contact->save();

        return $contact;
    }


    /**
     * @param array $params
     * @param Contact $contact
     * @return bool
     */
    public function updateContact(array $params, Contact $contact) : bool
    {
        return $contact->update($params);
    }

}
