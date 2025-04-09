<?php

namespace App\Services\Workflow;

use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionTypeRepository;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Services\Workflow\Interfaces\IWorkflowPositionTypeService;
use Illuminate\Support\Collection;

class WorkflowPositionTypeService extends ServiceBase implements IWorkflowPositionTypeService
{
    private IWorkflowPositionTypeRepository $workflowPositionTypeRepo;

    /**
     * WorkflowPositionTypeService constructor.
     *
     * @param IWorkflowPositionTypeRepository $workflowPositionTypeRepo
     */
    public function __construct(IWorkflowPositionTypeRepository $workflowPositionTypeRepo){
        parent::__construct();
        $this->workflowPositionTypeRepo = $workflowPositionTypeRepo;
    }

    /**
     * List all the WorkflowPositionTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->workflowPositionTypeRepo->listWorkflowPositionTypes($order, $sort, $columns);
    }

    /**
     * List all the WorkflowPositionTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listActiveWorkflowPositionTypes(string $order = 'updated_at', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->listWorkflowPositionTypes($order, $sort, $columns)
            ->where('is_active', '==', 1);
    }

    /**
     * Create WorkflowPositionType
     *
     * @param array $params
     *
     * @return Response
     */
    public function createWorkflowPositionType(array $params): Response
    {
        //Declaration
        $workflowPositionType = $this->workflowPositionTypeRepo->create($params);
        return $this->buildCreateResponse($workflowPositionType);
    }


    /**
     * Find the WorkflowPositionType by id
     *
     * @param int $id
     *
     * @return WorkflowPositionType
     */
    public function findWorkflowPositionTypeById(int $id): WorkflowPositionType
    {
        return $this->workflowPositionTypeRepo->findOneOrFail($id);
    }


    /**
     * Update WorkflowPositionType
     *
     * @param array $params
     *
     * @param WorkflowPositionType $workflowPositionType
     * @return Response
     */
    public function updateWorkflowPositionType(array $params, WorkflowPositionType $workflowPositionType): Response
    {
        $result = $this->workflowPositionTypeRepo->update($params, $workflowPositionType->id);
        return $this->buildUpdateResponse($workflowPositionType, $result);
    }

    /**
     * @param WorkflowPositionType $workflowPositionType
     * @return Response
     */
    public function deleteWorkflowPositionType(WorkflowPositionType $workflowPositionType): Response
    {
        $result = $this->workflowPositionTypeRepo->delete($workflowPositionType->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->workflowPositionTypeRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
