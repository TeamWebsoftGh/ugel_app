<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowPositionTypeRepository extends IBaseRepository
{
    public function listWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']): Collection;
}
