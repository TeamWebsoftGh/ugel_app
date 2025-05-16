<?php

namespace App\Services\CustomerService\Interfaces;

use App\Models\CustomerService\MaintenanceRequest;
use App\Services\Interfaces\IBaseService;

interface IMaintenanceService extends IBaseService
{
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createMaintenance(array $params);

    public function findMaintenanceById(int $id) : MaintenanceRequest;

    public function updateMaintenance(array $params, MaintenanceRequest $maintenance);

    public function deleteMaintenance(MaintenanceRequest $maintenance);

    public function getCreateMaintenance();

    public function deleteMultipleRequests(array $ids);
}
