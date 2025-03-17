<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Traits\UploadableTrait;
use App\Models\Workflow\WorkflowType;
use App\Repositories\Interfaces\IWorkflowTypeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IWorkflowTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WorkflowTypeService extends ServiceBase implements IWorkflowTypeService
{
    use UploadableTrait;

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
    public function listWorkflowTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
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
    public function listActiveWorkflowTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
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
        //Declaration
        $workflowType = null;

        //Process Request
        try {
            $params['code'] = Str::slug($params['code']??$params['name']);
            $workflowType = $this->workflowTypeRepo->createWorkflowType($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new WorkflowType(), 'create-workflow-type-failed');
        }

        //Check if WorkflowType was created successfully
        if (!$workflowType)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-workflow-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $workflowType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $workflowType;

        return $this->response;
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
        return $this->workflowTypeRepo->findWorkflowTypeById($id);
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
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->workflowTypeRepo->updateWorkflowType($params, $workflowType->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $workflowType, 'update-workflow-type-failed');
        }

        //Check if WorkflowType was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-workflow-type-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $workflowType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param WorkflowType $workflowType
     * @return Response
     */
    public function deleteWorkflowType(WorkflowType $workflowType): Response
    {
        //Declaration
        if ($this->workflowTypeRepo->deleteWorkflowType($workflowType->id))
        {
            //Audit Trail
            $logAction = 'delete-workflow-type-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $workflowType, $logAction);
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
    private function getSubjectType(array $params): array
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
