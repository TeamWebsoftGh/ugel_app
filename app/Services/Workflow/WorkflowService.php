<?php

namespace App\Services\Workflow;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Workflow\Workflow;
use App\Repositories\Workflow\Interfaces\IWorkflowRepository;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Services\Workflow\Interfaces\IWorkflowService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;

class WorkflowService extends ServiceBase implements IWorkflowService
{
    private IWorkflowRepository $workflowRepo;

    /**
     * WorkflowService constructor.
     *
     * @param IWorkflowRepository $workflowRepo
     */
    public function __construct(IWorkflowRepository $workflowRepo){
        parent::__construct();
        $this->workflowRepo = $workflowRepo;
    }

    /**
     * List all the Workflows
     *
     * @param array $filter
     * @param string $order
     * @param string $sort
     *
     * @return
     */
    public function listWorkflows(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->workflowRepo->listWorkflows($filter, $order, $sort);
    }

    /**
     * List all the Workflows
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listActiveWorkflows(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->listWorkflows($filter, $sort, $sort)
            ->where('is_active', '==', 1);
    }

    /**
     * Create Workflow
     *
     * @param array $params
     *
     * @return Response
     */
    public function createWorkflow(array $params): Response
    {
        //Declaration
        $workflow = $this->workflowRepo->create($params);
        return $this->buildCreateResponse($workflow);
    }


    /**
     * Find the Workflow by id
     *
     * @param int $id
     *
     * @return Workflow
     */
    public function findWorkflowById(int $id): Workflow
    {
        return $this->workflowRepo->findOneOrFail($id);
    }


    /**
     * Update Workflow
     *
     * @param array $params
     *
     * @param Workflow $workflow
     * @return Response
     */
    public function updateWorkflow(array $params, Workflow $workflow): Response
    {
        $result = $this->workflowRepo->update($params, $workflow->id);

        return $this->buildUpdateResponse($workflow, $result);
    }

    /**
     * @param Workflow $workflow
     * @return Response
     */
    public function deleteWorkflow(Workflow $workflow): Response
    {
        //Declaration
        $result = $this->workflowRepo->delete($workflow->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->workflowRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
