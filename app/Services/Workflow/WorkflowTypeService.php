<?php

namespace App\Services\Workflow;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Workflow\WorkflowType;
use App\Repositories\Workflow\Interfaces\IWorkflowTypeRepository;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Services\Workflow\Interfaces\IWorkflowTypeService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WorkflowTypeService extends ServiceBase implements IWorkflowTypeService
{
    private IWorkflowTypeRepository $workflowTypeRepo;

    /**
     * WorkflowTypeService constructor.
     *
     * @param IWorkflowTypeRepository $workflowTypeRepo
     */
    public function __construct(IWorkflowTypeRepository $workflowTypeRepo){
        parent::__construct();
        $this->workflowTypeRepo = $workflowTypeRepo;
    }

    /**
     * List all the WorkflowTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflowTypes(string $order = 'updated_at', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->workflowTypeRepo->listWorkflowTypes($order, $sort, $columns);
    }

    /**
     * List all the WorkflowTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listActiveWorkflowTypes(string $order = 'updated_at', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->listWorkflowTypes($order, $sort, $columns)
            ->where('is_active', '==', 1);
    }

    /**
     * Create WorkflowType
     *
     * @param array $params
     *
     * @return Response
     */
    public function createWorkflowType(array $params): Response
    {
        $workflowType = $this->workflowTypeRepo->create($params);
        return $this->buildCreateResponse($workflowType);
    }


    /**
     * Find the WorkflowType by id
     *
     * @param int $id
     *
     * @return WorkflowType
     */
    public function findWorkflowTypeById(int $id): WorkflowType
    {
        return $this->workflowTypeRepo->findOneOrFail($id);
    }


    /**
     * Update WorkflowType
     *
     * @param array $params
     *
     * @param WorkflowType $workflowType
     * @return Response
     */
    public function updateWorkflowType(array $params, WorkflowType $workflowType): Response
    {
        $result = $this->workflowTypeRepo->update($params, $workflowType->id);
        return $this->buildUpdateResponse($workflowType, $result);
    }

    /**
     * @param WorkflowType $workflowType
     * @return Response
     */
    public function deleteWorkflowType(WorkflowType $workflowType): Response
    {
        $result = $this->workflowTypeRepo->delete($workflowType->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->workflowTypeRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }

}
