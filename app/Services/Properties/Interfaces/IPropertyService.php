<?php

namespace App\Services\Properties\Interfaces;

use App\Models\Property\Property;
use App\Services\Interfaces\IBaseService;

interface IPropertyService extends IBaseService
{
    public function listProperties(array $filter = [], string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']);

    public function createProperty(array $params);

    public function findPropertyById(int $id);

    public function updateProperty(array $params, Property $property);

    public function deleteProperty(Property $property);
}
