<?php

namespace App\Services\Interfaces;

use App\Models\Property\Complaint;
use Illuminate\Support\Collection;

interface IComplaintService extends IBaseService
{
    public function listComplaints(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createComplaint(array $params);

    public function updateComplaint(array $params, Complaint $complaint);

    public function findComplaintById(int $id);

    public function deleteComplaint(Complaint $complaint);
}
