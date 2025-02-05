<?php

namespace App\Services\Interfaces;

use App\Models\Employees\Employee;
use App\Models\Timesheet\Leave;
use Illuminate\Support\Collection;

interface ILeaveService extends IBaseService
{
    public function listLeaves(array $filter = null, string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listLeaveBalances($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createLeave(array $params);

    public function updateLeave(array $params, Leave $Leave);

    public function findLeaveById(int $id);

    public function deleteLeave(Leave $Leave);

    public function createUpdateLeaveBalances(array $data);

    public function getAnnualLeave(Employee $employee);

    public function checkForHolidayOrWeekend(array $data);

    public function getLeaveEndDate(array $data);

    public function getLeaveResumeDate(array $data);

    public function getCreateLeave(array $data = null);
}
