<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization\Department;
use Illuminate\Support\Collection;

interface IDepartmentRepository extends IBaseRepository
{
    public function listDepartments(string $order = 'id', string $sort = 'desc'): Collection;

    public function createDepartment(array $params) : Department;

    public function findDepartmentById(int $id) : Department;

    public function updateDepartment(array $params, Department $department) : bool;

    public function deleteDepartment(Department $department);
}
