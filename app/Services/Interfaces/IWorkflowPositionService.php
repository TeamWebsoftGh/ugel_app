<?php

namespace App\Services\Interfaces;

use App\Models\Workflow\WorkflowPosition;
use Illuminate\Support\Collection;

interface IWorkflowPositionService extends IBaseService
{
    public function listWorkflowPositions(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function createWorkflowPosition(array $params);

    public function updateWorkflowPosition(array $params, WorkflowPosition $workflowPosition);

    public function findWorkflowPositionById(int $id);

    public function deleteWorkflowPosition(WorkflowPosition $workflowPosition);

}
