<?php

namespace App\Repositories\Interfaces;

use App\Models\CustomerService\VisitorLog;
use Illuminate\Support\Collection;

interface IVisitorLogRepository extends IBaseRepository
{
    public function listVisitorLogs(array $filter, string $order = 'id', string $sort = 'desc'): Collection;

    public function createVisitorLog(array $params) : VisitorLog;

    public function findVisitorLogById(int $id) : VisitorLog;

    public function updateVisitorLog(array $params, VisitorLog $VisitorLog) : bool;

    public function deleteVisitorLog(VisitorLog $VisitorLog);
}
