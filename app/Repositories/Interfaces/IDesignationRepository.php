<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization\Designation;
use Illuminate\Support\Collection;

interface IDesignationRepository extends IBaseRepository
{
    public function listDesignations(string $order = 'id', string $sort = 'desc'): Collection;

    public function createDesignation(array $params) : Designation;

    public function findDesignationById(int $id) : Designation;

    public function updateDesignation(array $params, Designation $designation) : bool;

    public function deleteDesignation(Designation $designation);
}
