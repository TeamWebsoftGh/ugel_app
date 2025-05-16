<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\WorkflowType;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowTypeRepository extends IBaseRepository
{
    public function listWorkflowTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;
}
