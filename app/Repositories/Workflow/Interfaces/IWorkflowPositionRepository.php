<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\WorkflowPosition;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowPositionRepository extends IBaseRepository
{
    public function listWorkflowPositions(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createWorkflowPosition(array $params) : WorkflowPosition;

    public function findWorkflowPositionById(int $id) : WorkflowPosition;

    public function updateWorkflowPosition(array $params, int $id) : bool;

    public function deleteWorkflowPosition(int $id);
}
