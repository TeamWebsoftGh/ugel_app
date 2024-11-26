<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\PropertyDetail;
use Illuminate\Support\Collection;

interface IPropertyLeaseRepository extends IBaseRepository
{
    public function listPropertyLeases(array $filter = [], string $order = 'id', string $sort = 'desc'): Collection;

    public function createPropertyLease(array $data) : PropertyLease;

    public function findPropertyLeaseById(int $id) : PropertyLease;

    public function updatePropertyLease(array $data, PropertyLease $propertyLease) : bool;

    public function deletePropertyLease(PropertyLease $propertyLease);

}
