<?php

namespace App\Services\Workflow;

use App\Repositories\Workflow\Interfaces\IWorkflowRequestRepository;
use App\Services\ServiceBase;
use App\Services\Workflow\Interfaces\IWorkflowRequestService;

class WorkflowRequestService extends ServiceBase implements IWorkflowRequestService
{
    private IWorkflowRequestRepository $workflowRequestRepo;

    /**
     * WorkflowService constructor.
     *
     * @param IWorkflowRequestRepository $workflowRequestRepo
     */
    public function __construct(IWorkflowRequestRepository $workflowRequestRepo){
        parent::__construct();
        $this->workflowRequestRepo = $workflowRequestRepo;
    }

    /**
     * List all the Workflows
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return mixed
     */
    public function listWorkflowRequests(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        if(!user()->can('read-workflow-requests'))
        {
            $filter['filter_created_by'] = user_id();
        }
        return $this->workflowRequestRepo->listWorkflowRequests($filter, $order, $sort);
    }

    /**
     * List all the Workflows
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return mixed
     */
    public function listWorkflowRequestDetails(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->workflowRequestRepo->listWorkflowRequestDetails($filter, $order, $sort);
    }

}
