<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Complaint;
use Illuminate\Support\Collection;

interface IComplaintRepository extends IBaseRepository
{
    public function findComplaintById(int $id);

    public function listComplaints(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createComplaint(array $params);

    public function updateComplaint(array $params, Complaint $complaint);

    public function deleteComplaint(Complaint $complaint);
}
