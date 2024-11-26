<?php

namespace App\Services\Interfaces;

use App\Models\Property\PropertyDetail;
use Illuminate\Support\Collection;

interface IPropertyLeaseService extends IBaseService
{
    public function listPropertyLeases(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createPropertyLease(array $params);

    public function findPropertyLeaseById(int $id);

    public function updatePropertyLease(array $params, PropertyLease $propertyLease);

    public function deletePropertyLease(PropertyLease $propertyLease);
}
