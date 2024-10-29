<?php

namespace App\Repositories\Interfaces;

use App\Models\Employees\Employee;
use Illuminate\Support\Collection;

interface IEmployeeRepository extends IBaseRepository
{
    public function listEmployees(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function listExitedEmployees(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function listAllEmployees(string $order = 'id', string $sort = 'desc'): Collection;

    public function createEmployee(array $params) : Employee;

    public function findEmployeeById(int $id) : Employee;

    public function updateEmployee(array $params, Employee $employee) : bool;
}
