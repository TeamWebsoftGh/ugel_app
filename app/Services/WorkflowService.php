<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Workflow\Workflow;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IWorkflowRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IWorkflowService;
use Illuminate\Support\Collection;

class WorkflowService extends ServiceBase implements IWorkflowService
{
    use UploadableTrait;

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
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listWorkflows(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->workflowRepo->listWorkflows($order, $sort, $columns);
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
    public function listActiveWorkflows(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
    {
        return $this->listWorkflows($order, $sort, $columns)
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
        $workflow = null;

        //Process Request
        try {
            $params['is_active'] = $params['status'];

            $workflow = $this->workflowRepo->createWorkflow($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Workflow(), 'create-workflow-failed');
        }

        //Check if Workflow was created successfully
        if (!$workflow)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-workflow-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $workflow, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $workflow;

        return $this->response;
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
        return $this->workflowRepo->findWorkflowById($id);
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
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['is_active'] = $params['status'];
            $result = $this->workflowRepo->updateWorkflow($params, $workflow->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $workflow, 'update-workflow-position-failed');
        }

        //Check if Workflow was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-workflow-position-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $workflow, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Workflow $workflow
     * @return Response
     */
    public function deleteWorkflow(Workflow $workflow): Response
    {
        //Declaration
        if ($this->workflowRepo->deleteWorkflow($workflow->id))
        {
            //Audit Trail
            $logAction = 'delete-workflow-position-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $workflow, $logAction);
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
        if ($params['position_type'] == 'hod') {
            $department = Department::find($params['category']);
            $department->department_head = $params['employee_id'];
            $department->save();

            $params['subject_type'] = get_class($department);
            $params['subject_id'] = $department->id;
        }
        if ($params['position_type'] == 'branch-manager') {
            $location = Branch::find($params['category']);
            $location->location_head = $params['employee_id'];
            $location->save();

            $params['subject_type'] = get_class($location);
            $params['subject_id'] = $location->id;
        }
        return $params;
    }
}
