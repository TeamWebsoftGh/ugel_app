<?php

namespace App\Services\Interfaces;

use App\Models\Organization\Designation;
use Illuminate\Support\Collection;

interface IDesignationService extends IBaseService
{
    public function listDesignations(string $order = 'id', string $sort = 'desc'): Collection;

    public function createDesignation(array $params);

    public function findDesignationById(int $id) : Designation;

    public function updateDesignation(array $params, Designation $designation);

    public function deleteDesignation(Designation $designation);
}
