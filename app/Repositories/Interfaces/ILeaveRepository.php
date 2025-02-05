<?php

namespace App\Repositories\Interfaces;

use App\Models\Timesheet\Leave;
use Illuminate\Support\Collection;

interface ILeaveRepository extends IBaseRepository
{
    public function listLeaves(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function listLeaveBalances(int $leaveType, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createLeave(array $params) : Leave;

    public function findLeaveById(int $id) : Leave;

    public function updateLeave(array $params, int $id) : bool;

    public function deleteLeave(int $id);
}
