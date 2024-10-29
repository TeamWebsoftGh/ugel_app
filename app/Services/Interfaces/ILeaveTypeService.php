<?php

namespace App\Services\Interfaces;

use App\Models\Timesheet\LeaveType;
use Illuminate\Support\Collection;

interface ILeaveTypeService extends IBaseService
{
    public function listLeaveTypes(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listLeaveTypesDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createLeaveType(array $params);

    public function updateLeaveType(array $params, LeaveType $LeaveType);

    public function findLeaveTypeById(int $id);

    public function deleteLeaveType(LeaveType $LeaveType);

    public function createUpdateLeaveTypeDetails(array $data, LeaveType $LeaveType);

    public function deleteLeaveTypeDetail(int $id);
}
