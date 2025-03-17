<?php

namespace App\Services\Properties\Interfaces;

use App\Models\Property\Propertyunit;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IPropertyUnitService extends IBaseService
{
    public function listPropertyUnits(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc');

    public function createPropertyUnit(array $params);

    public function findPropertyUnitById(int $id);

    public function updatePropertyUnit(array $params, PropertyUnit $propertyUnit);

    public function deletePropertyUnit(PropertyUnit $propertyUnit);
}
