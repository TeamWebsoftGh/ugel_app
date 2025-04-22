<?php

namespace App\Services\Workflow\Interfaces;

use App\Models\Workflow\Workflow;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IWorkflowService extends IBaseService
{
    public function listWorkflows(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function listActiveWorkflows(array $filter = [], string $order = 'updated_at', string $sort = 'desc') : Collection;

    public function createWorkflow(array $params);

    public function updateWorkflow(array $params, Workflow $workflow);

    public function findWorkflowById(int $id);

    public function deleteWorkflow(Workflow $workflow);

    public function deleteMultiple(array $ids);

}
