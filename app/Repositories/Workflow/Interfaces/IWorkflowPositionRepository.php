<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\WorkflowPosition;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowPositionRepository extends IBaseRepository
{
    public function listWorkflowPositions(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
