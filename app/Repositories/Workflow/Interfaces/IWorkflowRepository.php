<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\Workflow;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowRepository extends IBaseRepository
{
    public function listWorkflows(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createWorkflow(array $params) : Workflow;

    public function findWorkflowById(int $id) : Workflow;

    public function updateWorkflow(array $params, int $id) : bool;

    public function deleteWorkflow(int $id);
}
