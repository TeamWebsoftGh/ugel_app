<?php

namespace App\Services\Workflow\Interfaces;

use App\Services\Interfaces\IBaseService;

interface IWorkflowRequestService extends IBaseService
{
    public function listWorkflowRequests(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function listWorkflowRequestDetails(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
