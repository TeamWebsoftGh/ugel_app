<?php

namespace App\Repositories\Interfaces;

use App\Models\Timesheet\LeaveSchedule;
use Illuminate\Support\Collection;

interface ILeaveScheduleRepository extends IBaseRepository
{
    public function listLeaveSchedules(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createLeaveSchedule(array $params) : LeaveSchedule;

    public function findLeaveScheduleById(int $id) : LeaveSchedule;

    public function updateLeaveSchedule(array $params, int $id) : bool;

    public function deleteLeaveSchedule(int $id);
}
