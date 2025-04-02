<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\Team;
use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Workflow\WorkflowPosition;
use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IWorkflowPositionService;
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
    public function listWorkflowPositions(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->workflowPositionRepo->listWorkflowPositions($order, $sort, $columns);
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
        $workflowPosition = null;

        //Process Request
        try {
            $params = $this->getCategory($params);
            $params['is_active'] = $params['status'];

            $workflowPosition = $this->workflowPositionRepo->createWorkflowPosition($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new WorkflowPosition(), 'create-workflow-position-failed');
        }

        //Check if WorkflowPosition was created successfully
        if (!$workflowPosition)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-workflow-position-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $workflowPosition, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $workflowPosition;

        return $this->response;
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
        return $this->workflowPositionRepo->findWorkflowPositionById($id);
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
        //Declaration
        $result = false;

        //Process Request
        try {
            $params = $this->getCategory($params);
            $result = $this->workflowPositionRepo->updateWorkflowPosition($params, $workflowPosition->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $workflowPosition, 'update-workflow-position-failed');
        }

        //Check if WorkflowPosition was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-workflow-position-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $workflowPosition, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param WorkflowPosition $workflowPosition
     * @return Response
     */
    public function deleteWorkflowPosition(WorkflowPosition $workflowPosition): Response
    {
        //Declaration
        if ($this->workflowPositionRepo->deleteWorkflowPosition($workflowPosition->id))
        {
            //Audit Trail
            $logAction = 'delete-workflow-position-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $workflowPosition, $logAction);
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;

            return $this->response;
        }

        $this->response->status = ResponseType::ERROR;
        $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

        return $this->response;
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
