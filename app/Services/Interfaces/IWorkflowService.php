<?php

namespace App\Services\Interfaces;

use App\Models\Workflow\Workflow;
use Illuminate\Support\Collection;

interface IWorkflowService extends IBaseService
{
    public function listWorkflows(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listActiveWorkflows(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function createWorkflow(array $params);

    public function updateWorkflow(array $params, Workflow $workflow);

    public function findWorkflowById(int $id);

    public function deleteWorkflow(Workflow $workflow);

}
