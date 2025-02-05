<?php

namespace App\Services\Interfaces;

use App\Models\Property\Propertyunit;
use Illuminate\Support\Collection;

interface IPropertyUnitService extends IBaseService
{
    public function listPropertyUnits(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createPropertyUnit(array $params);

    public function findPropertyUnitById(int $id);

    public function updatePropertyUnit(array $params, PropertyUnit $propertyUnit);

    public function deletePropertyUnit(PropertyUnit $propertyUnit);
}
