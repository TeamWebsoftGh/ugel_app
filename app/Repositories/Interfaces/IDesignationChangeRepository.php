<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\DesignationChange;
use Illuminate\Support\Collection;

interface IDesignationChangeRepository extends IBaseRepository
{
    public function listDesignationChanges(array $filter = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createDesignationChange(array $params) : DesignationChange;

    public function findDesignationChangeById(int $id) : DesignationChange;

    public function updateDesignationChange(array $params, DesignationChange $designationChange) : bool;

    public function deleteDesignationChange(DesignationChange $designationChange);
}
