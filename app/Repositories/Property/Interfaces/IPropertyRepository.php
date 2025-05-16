<?php

namespace App\Repositories\Property\Interfaces;

use App\Models\Property\Property;
use App\Repositories\Interfaces\IBaseRepository;

interface IPropertyRepository extends IBaseRepository
{
    public function listProperties(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createProperty(array $data) : Property;

    public function findPropertyById(int $id) : Property;

    public function updateProperty(array $data, Property $property) : bool;

    public function deleteProperty(Property $property);

}
