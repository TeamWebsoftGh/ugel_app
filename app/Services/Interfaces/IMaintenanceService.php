<?php

namespace App\Services\Interfaces;

use App\Models\CustomerService\MaintenanceRequest;

interface IMaintenanceService extends IBaseService
{
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createMaintenance(array $params);

    public function findMaintenanceById(int $id) : MaintenanceRequest;

    public function updateMaintenance(array $params, MaintenanceRequest $maintenance);

    public function deleteMaintenance(MaintenanceRequest $maintenance);

    public function getCreateMaintenance();
}
