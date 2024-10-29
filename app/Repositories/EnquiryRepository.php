<?php

namespace App\Repositories;

use App\Models\CustomerService\Enquiry;
use App\Repositories\Interfaces\IEnquiryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class EnquiryRepository extends BaseRepository implements IEnquiryRepository
{
    Public function __construct(Enquiry $enquiry)
    {
        parent::__construct($enquiry);
        $this->model = $enquiry;
    }

    public function listEnquiryMessages(string $order = 'id', string $sort = 'desc'):Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * @param int $id
     * @return Enquiry
     * @throws ModelNotFoundException
     */
    public function findEnquiryById(int $id) : Enquiry
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Enquiry
     */
    public function createEnquiry(array $params) : Enquiry
    {
        $enquiry = new Enquiry($params);
        $enquiry->save();

        return $enquiry;
    }


    /**
     * @param array $params
     * @param Enquiry $enquiry
     * @return bool
     */
    public function updateEnquiry(array $params, Enquiry $enquiry) : bool
    {
        return $enquiry->update($params);
    }

}
