<?php

namespace App\Repositories\Interfaces;

use App\Models\ServiceType;
use Illuminate\Support\Collection;

interface IServiceTypeRepository extends IBaseRepository
{
    public function findServiceTypeById(int $id);

    public function listServiceTypes(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createServiceType(array $params);

    public function updateServiceType(array $params, ServiceType $serviceType);

    public function deleteServiceType(ServiceType $serviceType);
}
