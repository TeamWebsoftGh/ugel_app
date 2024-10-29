<?php

namespace App\Services\Interfaces;

use App\Models\Employees\Employee;
use Illuminate\Support\Collection;

interface IEmployeeDetailService extends IBaseService
{
    public function listEmployees(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createEmployee(array $params);

    public function findEmployeeById(int $id) : Employee;

    public function updateEmployee(array $params, Employee $employee);

    public function deleteEmployee(Employee $employee);
}
