<?php

namespace App\Services\Interfaces;

use App\Models\CustomerService\VisitorLog;
use Illuminate\Support\Collection;

interface IVisitorLogService extends IBaseService
{
    public function listVisitorLogs(array $filter, string $order = 'id', string $sort = 'desc'): Collection;

    public function createVisitorLog(array $params);

    public function findVisitorLogById(int $id);

    public function findVisitorLogByStaffId(string $staff_id);

    public function updateVisitorLog(array $params, VisitorLog $VisitorLog);

    public function deleteVisitorLog(VisitorLog $VisitorLog);
}
