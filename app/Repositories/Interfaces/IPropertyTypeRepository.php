<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\PropertyType;
use Illuminate\Support\Collection;

interface IPropertyTypeRepository extends IBaseRepository
{
    public function listPropertyTypes(array $filter = [], string $order = 'id', string $sort = 'desc'): Collection;

    public function createPropertyType(array $data) : PropertyType;

    public function findPropertyTypeById(int $id) : PropertyType;

    public function updatePropertyType(array $data, PropertyType $propertyType) : bool;

    public function deletePropertyType(PropertyType $propertyType);

}
