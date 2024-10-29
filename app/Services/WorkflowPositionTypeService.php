<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Organization\Department;
use App\Models\Organization\Subsidiary;
use App\Traits\UploadableTrait;
use App\Models\Workflow\WorkflowPositionType;
use App\Repositories\Interfaces\IWorkflowPositionTypeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IWorkflowPositionTypeService;
use Illuminate\Support\Collection;

class WorkflowPositionTypeService extends ServiceBase implements IWorkflowPositionTypeService
{
    use UploadableTrait;

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
    public function listWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
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
    public function listActiveWorkflowPositionTypes(string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection
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
        $workflowPositionType = null;

        //Process Request
        try {
            $params['is_active'] = $params['status'];

            $workflowPositionType = $this->workflowPositionTypeRepo->createWorkflowPositionType($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new WorkflowPositionType(), 'create-workflow-position-failed');
        }

        //Check if WorkflowPositionType was created successfully
        if (!$workflowPositionType)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-workflow-position-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $workflowPositionType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $workflowPositionType;

        return $this->response;
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
        return $this->workflowPositionTypeRepo->findWorkflowPositionTypeById($id);
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
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['is_active'] = $params['status'];
            $result = $this->workflowPositionTypeRepo->updateWorkflowPositionType($params, $workflowPositionType->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $workflowPositionType, 'update-workflow-position-failed');
        }

        //Check if WorkflowPositionType was updated successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-workflow-position-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $workflowPositionType, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param WorkflowPositionType $workflowPositionType
     * @return Response
     */
    public function deleteWorkflowPositionType(WorkflowPositionType $workflowPositionType): Response
    {
        //Declaration
        if ($this->workflowPositionTypeRepo->deleteWorkflowPositionType($workflowPositionType->id))
        {
            //Audit Trail
            $logAction = 'delete-workflow-position-successful';
            $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

            log_activity($auditMessage, $workflowPositionType, $logAction);
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

        if ($params['position_type'] == 'general-manager') {
            $subsidiary = Subsidiary::find($params['category']);
            $subsidiary->general_manager_id = $params['employee_id'];
            $subsidiary->save();

            $params['subject_type'] = get_class($subsidiary);
            $params['subject_id'] = $subsidiary->id;
        }
        return $params;
    }
}
