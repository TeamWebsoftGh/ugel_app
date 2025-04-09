<?php

namespace App\Services\Workflow\Interfaces;

use App\Models\Workflow\WorkflowType;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IWorkflowTypeService extends IBaseService
{
    public function listWorkflowTypes(string $order = 'updated_at', string $sort = 'desc', $columns = []) : Collection;

    public function listActiveWorkflowTypes(string $order = 'updated_at', string $sort = 'desc', $columns = []) : Collection;

    public function createWorkflowType(array $params);

    public function updateWorkflowType(array $params, WorkflowType $workflowType);

    public function findWorkflowTypeById(int $id);

    public function deleteWorkflowType(WorkflowType $workflowType);

    public function deleteMultiple(array $ids);

}
