<?php

namespace App\Repositories;

use App\Models\Property\Complaint;
use App\Repositories\Interfaces\IComplaintRepository;
use Illuminate\Support\Collection;

class ComplaintRepository extends BaseRepository implements IComplaintRepository
{
    /**
     * ComplaintRepository constructor.
     *
     * @param Complaint $complaint
     */
    public function __construct(Complaint $complaint)
    {
        parent::__construct($complaint);
        $this->model = $complaint;
    }

    /**
     * Find the AdmissionPeriod by id
     *
     * @param int $id
     *
     * @return Complaint
     */
    public function findComplaintById(int $id): Complaint
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Complaint
     */
    public function createComplaint(array $data) : Complaint
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Complaint $complaint
     * @return bool
     */
    public function updateComplaint(array $data, Complaint $complaint) : bool
    {
        return $complaint->update($data);
    }

    /**
     * @param Complaint $complaint
     *
     * @return bool
     */
    public function deleteComplaint(Complaint $complaint) : bool
    {
        return $this->delete($complaint->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listComplaints(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }

}
