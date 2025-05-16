<?php

namespace App\Repositories\Workflow\Interfaces;

use App\Repositories\Interfaces\IBaseRepository;

interface IWorkflowRequestRepository extends IBaseRepository
{
    public function listWorkflowRequests(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function listWorkflowRequestDetails(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
