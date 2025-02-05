<?php

namespace App\Repositories\Interfaces;

use App\Models\Workflow\WorkflowPositionType;
use Illuminate\Support\Collection;

interface IWorkflowPositionTypeRepository extends IBaseRepository
{
    public function listWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createWorkflowPositionType(array $params) : WorkflowPositionType;

    public function findWorkflowPositionTypeById(int $id) : WorkflowPositionType;

    public function updateWorkflowPositionType(array $params, int $id) : bool;

    public function deleteWorkflowPositionType(int $id);
}
