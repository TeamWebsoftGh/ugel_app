<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\PropertyUnit;
use Illuminate\Support\Collection;

interface IPropertyUnitRepository extends IBaseRepository
{
    public function listPropertyUnits(array $filter = [], string $order = 'id', string $sort = 'desc'): Collection;

    public function createPropertyUnit(array $data) : PropertyUnit;

    public function findPropertyUnitById(int $id) : PropertyUnit;

    public function updatePropertyUnit(array $data, PropertyUnit $propertyUnit) : bool;

    public function deletePropertyUnit(PropertyUnit $propertyUnit);

}
