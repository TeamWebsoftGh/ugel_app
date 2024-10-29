<?php

namespace App\Repositories\Interfaces;

use App\Models\Workflow\WorkflowType;
use Illuminate\Support\Collection;

interface IWorkflowTypeRepository extends IBaseRepository
{
    public function listWorkflowTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createWorkflowType(array $params) : WorkflowType;

    public function findWorkflowTypeById(int $id) : WorkflowType;

    public function updateWorkflowType(array $params, int $id) : bool;

    public function deleteWorkflowType(int $id);
}
