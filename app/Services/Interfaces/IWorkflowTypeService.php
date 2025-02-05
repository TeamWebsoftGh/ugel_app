<?php

namespace App\Services\Interfaces;

use App\Models\Workflow\WorkflowType;
use Illuminate\Support\Collection;

interface IWorkflowTypeService extends IBaseService
{
    public function listWorkflowTypes(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listActiveWorkflowTypes(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function createWorkflowType(array $params);

    public function updateWorkflowType(array $params, WorkflowType $workflowType);

    public function findWorkflowTypeById(int $id);

    public function deleteWorkflowType(WorkflowType $workflowType);

}
