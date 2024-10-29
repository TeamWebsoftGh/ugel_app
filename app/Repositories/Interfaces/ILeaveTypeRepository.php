<?php

namespace App\Repositories\Interfaces;

use App\Models\Timesheet\LeaveType;
use Illuminate\Support\Collection;

interface ILeaveTypeRepository extends IBaseRepository
{
    public function listLeaveTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createLeaveType(array $params) : LeaveType;

    public function findLeaveTypeById(int $id) : LeaveType;

    public function updateLeaveType(array $params, int $id) : bool;

    public function deleteLeaveType(int $id);
}
