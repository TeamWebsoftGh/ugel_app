<?php

namespace App\Services\Interfaces;

use App\Models\Property\DesignationChange;
use Illuminate\Support\Collection;

interface IDesignationChangeService extends IBaseService
{
    public function listDesignationChanges(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createDesignationChange(array $params);

    public function findDesignationChangeById(int $id);

    public function updateDesignationChange(array $params, DesignationChange $designationChange);

    public function deleteDesignationChange(DesignationChange $designationChange);
}
