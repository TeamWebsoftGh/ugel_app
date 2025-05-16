<?php

namespace App\Repositories\Property\Interfaces;

use App\Models\Property\PropertyUnit;
use App\Repositories\Interfaces\IBaseRepository;

interface IPropertyUnitRepository extends IBaseRepository
{
    public function listPropertyUnits(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createPropertyUnit(array $data) : PropertyUnit;

    public function findPropertyUnitById(int $id) : PropertyUnit;

    public function updatePropertyUnit(array $data, PropertyUnit $propertyUnit) : bool;

    public function deletePropertyUnit(PropertyUnit $propertyUnit);

}
