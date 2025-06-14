<?php

namespace App\Repositories\CustomerService\Interfaces;

use App\Models\CustomerService\MaintenanceCategory;
use App\Repositories\Interfaces\IBaseRepository;

interface IMaintenanceCategoryRepository extends IBaseRepository
{
    public function listPropertyCategories(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createMaintenanceCategory(array $data) : MaintenanceCategory;

    public function findMaintenanceCategoryById(int $id) : MaintenanceCategory;

    public function updateMaintenanceCategory(array $data, MaintenanceCategory $maintenanceCategory) : bool;

    public function deleteMaintenanceCategory(MaintenanceCategory $maintenanceCategory);

}
