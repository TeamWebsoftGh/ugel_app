<?php

namespace App\Repositories\CustomerService\Interfaces;

use App\Models\CustomerService\MaintenanceRequest;
use App\Repositories\Interfaces\IBaseRepository;

interface IMaintenanceRepository extends IBaseRepository
{
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createMaintenance(array $data) : MaintenanceRequest;

    public function findMaintenanceById(int $id) : MaintenanceRequest;

    public function updateMaintenance(array $data, MaintenanceRequest $maintenance) : bool;

    public function deleteMaintenance(MaintenanceRequest $maintenance);
}
