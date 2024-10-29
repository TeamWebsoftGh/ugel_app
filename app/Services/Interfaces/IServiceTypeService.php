<?php

namespace App\Services\Interfaces;

use App\Models\ServiceType;
use Illuminate\Support\Collection;

interface IServiceTypeService extends IBaseService
{
    public function listServiceTypes(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createServiceType(array $params);

    public function updateServiceType(array $params, ServiceType $serviceType);

    public function findServiceTypeById(int $id);

    public function deleteServiceType(ServiceType $serviceType);
}
