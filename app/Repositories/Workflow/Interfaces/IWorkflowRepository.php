<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Models\Workflow\Workflow;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IWorkflowRepository extends IBaseRepository
{
    public function listWorkflows(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
