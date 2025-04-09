<?php

namespace App\Services\Workflow\Interfaces;

use App\Models\Workflow\WorkflowPositionType;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IWorkflowPositionTypeService extends IBaseService
{
    public function listWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', $columns = []) : Collection;

    public function listActiveWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', $columns = ['*']): Collection;

    public function createWorkflowPositionType(array $params);

    public function updateWorkflowPositionType(array $params, WorkflowPositionType $workflowPositionType);

    public function findWorkflowPositionTypeById(int $id);

    public function deleteWorkflowPositionType(WorkflowPositionType $workflowPositionType);

    public function deleteMultiple(array $ids);

}
