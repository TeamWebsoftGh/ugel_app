<?php

namespace App\Services\Interfaces\Properties;

use App\Models\Property\PropertyType;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IPropertyTypeService extends IBaseService
{
    public function listPropertyTypes(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createPropertyType(array $params);

    public function findPropertyTypeById(int $id);

    public function updatePropertyType(array $params, PropertyType $propertyType);

    public function deletePropertyType(PropertyType $propertyType);

    public function deleteMultiplePropertyTypes(array $ids);
}
