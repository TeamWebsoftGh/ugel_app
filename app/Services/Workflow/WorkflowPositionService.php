<?php

namespace App\Services\Workflow;

use App\Models\Auth\Team;
use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionRepository;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Services\Workflow\Interfaces\IWorkflowPositionService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;

class WorkflowPositionService extends ServiceBase implements IWorkflowPositionService
{
    use UploadableTrait;

    private IWorkflowPositionRepository $workflowPositionRepo;

    /**
     * WorkflowPositionService constructor.
     *
     * @param IWorkflowPositionRepository $workflowPositionRepo
     */
    public function __construct(IWorkflowPositionRepository $workflowPositionRepo){
        parent::__construct();
        $this->workflowPositionRepo = $workflowPositionRepo;
    }

    /**
     * List all the WorkflowPositions
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflowPositions(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->workflowPositionRepo->listWorkflowPositions($filter, $order, $sort);
    }

    /**
     * Create WorkflowPosition
     *
     * @param array $params
     *
     * @return Response
     */
    public function createWorkflowPosition(array $params): Response
    {
        //Declaration
        $params = $this->getCategory($params);
        $workflowPosition = $this->workflowPositionRepo->create($params);

        return $this->buildCreateResponse($workflowPosition);
    }


    /**
     * Find the WorkflowPosition by id
     *
     * @param int $id
     *
     * @return WorkflowPosition
     */
    public function findWorkflowPositionById(int $id): WorkflowPosition
    {
        return $this->workflowPositionRepo->findOneOrFail($id);
    }


    /**
     * Update WorkflowPosition
     *
     * @param array $params
     *
     * @param WorkflowPosition $workflowPosition
     * @return Response
     */
    public function updateWorkflowPosition(array $params, WorkflowPosition $workflowPosition): Response
    {
        $params = $this->getCategory($params);
        $result = $this->workflowPositionRepo->update($params, $workflowPosition->id);
        return $this->buildUpdateResponse($workflowPosition, $result);
    }

    /**
     * @param WorkflowPosition $workflowPosition
     * @return Response
     */
    public function deleteWorkflowPosition(WorkflowPosition $workflowPosition): Response
    {
        $result = $this->workflowPositionRepo->delete($workflowPosition->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->workflowPositionRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }

    /**
     * @param array $params
     * @return array
     */
    private function getCategory(array $params): array
    {
        $positionType = WorkflowPositionType::firstWhere('code', $params['workflow_position_type']);
        $params['workflow_position_type_id'] = $positionType->id;

        if ($params['workflow_position_type'] == 'country-manager') {
            $company = Company::find($params['category']);

            $params['subject_type'] = get_class($company);
            $params['subject_id'] = $company->id;
        }
        if ($params['workflow_position_type'] == 'hod') {
            $department = Department::find($params['category']);
            $department->department_head = $params['employee_id'];
            $department->save();

            $params['subject_type'] = get_class($department);
            $params['subject_id'] = $department->id;
        }

        if ($params['workflow_position_type'] == 'team') {
            $team = Team::find($params['category']);

            $params['subject_type'] = get_class($team);
            $params['subject_id'] = $team->id;
        }

        return $params;
    }
}
