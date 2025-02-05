<?php

namespace App\Services\Interfaces;

use App\Models\Audit\LogActivity;
use Illuminate\Support\Collection as Support;

interface IAuditService extends IBaseService
{
    public function listLogs(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Support;

    public function listLogsByType($type) : Support;

    public function createLog(array $params) : LogActivity;

    public function deleteLog(int $id) : LogActivity;

    public function findLogById(int $id) : LogActivity;

}
