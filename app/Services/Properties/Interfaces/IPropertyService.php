<?php

namespace App\Services\Properties\Interfaces;

use App\Models\Property\Property;
use App\Services\Interfaces\IBaseService;

interface IPropertyService extends IBaseService
{
    public function listProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc');

    public function listLeaseProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc');
    public function listOwnProperties(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc');

    public function createProperty(array $params);

    public function findPropertyById(int $id);

    public function updateProperty(array $params, Property $property);

    public function deleteProperty(Property $property);
}
