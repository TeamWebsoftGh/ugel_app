<?php

namespace App\Services\Workflow\Interfaces;

use App\Models\Workflow\WorkflowPosition;
use App\Services\Interfaces\IBaseService;

interface IWorkflowPositionService extends IBaseService
{
    public function listWorkflowPositions(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createWorkflowPosition(array $params);

    public function updateWorkflowPosition(array $params, WorkflowPosition $workflowPosition);

    public function findWorkflowPositionById(int $id);

    public function deleteWorkflowPosition(WorkflowPosition $workflowPosition);

    public function deleteMultiple(array $ids);

}
