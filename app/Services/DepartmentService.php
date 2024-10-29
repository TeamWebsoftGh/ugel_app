<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Department;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IDepartmentRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IDepartmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DepartmentService extends ServiceBase implements IDepartmentService
{
    use UploadableTrait;

    private IDepartmentRepository $departmentRepo;

    /**
     * DepartmentService constructor.
     *
     * @param IDepartmentRepository $departmentRepository
     */
    public function __construct(IDepartmentRepository $departmentRepository)
    {
        parent::__construct();
        $this->departmentRepo = $departmentRepository;
    }

    /**
     * List all the Departments
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listDepartments(string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->departmentRepo->listDepartments($order, $sort);
    }

    /**
     * Create the Departments
     *
     * @param array $params
     * @return Response
     */
    public function createDepartment(array $params)
    {
        //Declaration
        $department = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['department_name'].$params['subsidiary_id']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadPublic($params['cover_image'],'departments' , $params['slug']);
            }
            $department = $this->departmentRepo->createDepartment($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Department(), 'create-department-failed');
        }

        //Check if Department was created successfully
        if (!$department)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-department-successful';
        $auditMessage = 'You have successfully added a new department: '.$department->department_name;

        log_activity($auditMessage, $department, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $department;

        return $this->response;
    }


    /**
     * Find the Department by id
     *
     * @param int $id
     *
     * @return Department
     */
    public function findDepartmentById(int $id): Department
    {
        return $this->departmentRepo->findDepartmentById($id);
    }

    /**
     * Update Department
     *
     * @param array $params
     * @param Department $department
     * @return Response
     */
    public function updateDepartment(array $params, Department $department)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['department_name'].$params['subsidiary_id']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadPublic($params['cover_image'],'departments' , $params['slug']);
            }
            $result = $this->departmentRepo->updateDepartment($params, $department);
        } catch (\Exception $e) {
            log_error(format_exception($e), $department, 'update-department-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-department-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $department, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $department;

        return $this->response;
    }

    /**
     * @param Department $department
     * @return Response
     */
    public function deleteDepartment(Department $department)
    {
        //Declaration
        $result = false;
        try{
            if (count($department->users) > 0 || employee()->department_id == $department->id)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Department.";

                return $this->response;
            }

            $result = $this->departmentRepo->deleteDepartment($department);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $department, 'delete-department-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-department-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $department, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
