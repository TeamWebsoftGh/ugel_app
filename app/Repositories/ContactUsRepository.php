<?php

namespace App\Repositories;

use App\Models\ContactUs;
use App\Repositories\Interfaces\IContactUsRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ContactUsRepository extends BaseRepository implements IContactUsRepository
{
    Public function __construct(ContactUs $contactUs)
    {
        parent::__construct($contactUs);
        $this->model = $contactUs;
    }

    public function listContactUsMessages(string $order = 'id', string $sort = 'desc'):Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * @param int $id
     * @return ContactUs
     * @throws ModelNotFoundException
     */
    public function findContactUsById(int $id) : ContactUs
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return ContactUs
     */
    public function createContactUs(array $params) : ContactUs
    {
        $contactUs = new ContactUs($params);
        $contactUs->save();

        return $contactUs;
    }


    /**
     * @param array $params
     * @param ContactUs $contactUs
     * @return bool
     */
    public function updateContactUs(array $params, ContactUs $contactUs) : bool
    {
        return $contactUs->update($params);
    }

}
