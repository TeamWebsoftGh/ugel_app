<?php

namespace App\Services\Interfaces;

use App\Models\CustomerService\MaintenanceCategory;

interface IMaintenanceCategoryService extends IBaseService
{
    public function listMaintenanceCategories(array $filter = null, string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']);

    public function createMaintenanceCategory(array $data);

    public function findMaintenanceCategoryById(int $id);

    public function updateMaintenanceCategory(array $data, MaintenanceCategory $maintenanceCategory);

    public function deleteMaintenanceCategory(MaintenanceCategory $maintenanceCategory);


    public function deleteMultipleMaintenanceCategories(array $ids);
}
