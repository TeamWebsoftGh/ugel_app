<?php

namespace App\Services\Interfaces;

use App\Models\Workflow\WorkflowPositionType;
use Illuminate\Support\Collection;

interface IWorkflowPositionTypeService extends IBaseService
{
    public function listWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listActiveWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createWorkflowPositionType(array $params);

    public function updateWorkflowPositionType(array $params, WorkflowPositionType $workflowPositionType);

    public function findWorkflowPositionTypeById(int $id);

    public function deleteWorkflowPositionType(WorkflowPositionType $workflowPositionType);

}
