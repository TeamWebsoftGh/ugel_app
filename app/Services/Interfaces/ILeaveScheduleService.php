<?php

namespace App\Services\Interfaces;

use App\Models\Employees\Employee;
use App\Models\Timesheet\LeaveSchedule;
use Illuminate\Support\Collection;

interface ILeaveScheduleService extends IBaseService
{
    public function listLeaveSchedules(array $filter = null, string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function createLeaveSchedule(array $params);

    public function updateLeaveSchedule(array $params, LeaveSchedule $leaveSchedule);

    public function findLeaveScheduleById(int $id);

    public function deleteLeaveSchedule(LeaveSchedule $LeaveSchedule);

    public function getCreateLeaveSchedule(array $data = null);
}
