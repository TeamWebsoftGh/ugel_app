<?php

namespace App\Repositories\Interfaces;

use App\Models\Audit\LogActivity;
use Illuminate\Support\Collection as Support;

interface IAuditRepository extends IBaseRepository
{
    public function listLogs(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Support;

    public function listLogsByType($id) : Support;

    public function createLog(array $params) : LogActivity;

    public function deleteLog(int $id);

    public function findLogById(int $id) : LogActivity;

}
