<?php

namespace App\Services\Interfaces;

use App\Models\Organization\Department;
use Illuminate\Support\Collection;

interface IDepartmentService extends IBaseService
{
    public function listDepartments(string $order = 'id', string $sort = 'desc'): Collection;

    public function createDepartment(array $params);

    public function findDepartmentById(int $id) : Department;

    public function updateDepartment(array $params, Department $department);

    public function deleteDepartment(Department $department);
}
