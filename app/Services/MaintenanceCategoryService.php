<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\CustomerService\MaintenanceCategory;
use App\Repositories\Interfaces\IMaintenanceCategoryRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IMaintenanceCategoryService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;

class MaintenanceCategoryService extends ServiceBase implements IMaintenanceCategoryService
{
    use UploadableTrait;
    private IMaintenanceCategoryRepository $maintenanceCategoryRepo;

    /**
     * MaintenanceCategoryService constructor.
     * @param IMaintenanceCategoryRepository $maintenanceCategory
     */
    public function __construct(IMaintenanceCategoryRepository $maintenanceCategory)
    {
        parent::__construct();
        $this->maintenanceCategoryRepo = $maintenanceCategory;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listMaintenanceCategories(array $filter = null, string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*'])
    {
        return $this->maintenanceCategoryRepo->listPropertyCategories($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $params
     *
     * @return Response
     */
    public function createMaintenanceCategory(array $data)
    {
        //Declaration
        $maintenanceCategory = null;
        try{
            //Prepare request
            $maintenanceCategory = $this->maintenanceCategoryRepo->createMaintenanceCategory($data);

        }catch (\Exception $ex){
            log_error(format_exception($ex), new MaintenanceCategory(), 'create-maintenance-categories-failed');
        }

        //Check if Successful
        if ($maintenanceCategory == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-maintenance-categories-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $maintenanceCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $maintenanceCategory;

        return $this->response;
    }


    /**
     * @param array $data
     * @param MaintenanceCategory $maintenanceCategory
     * @return Response
     */
    public function updateMaintenanceCategory(array $params, MaintenanceCategory $maintenanceCategory)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->maintenanceCategoryRepo->updateMaintenanceCategory($params, $maintenanceCategory);
        }catch (\Exception $ex){
            log_error(format_exception($ex), new MaintenanceCategory(), 'create-maintenance-categories-failed');
        }

        //Check if Successful
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-maintenance-categories-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $maintenanceCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $maintenanceCategory;

        return $this->response;
    }


    /**
     * @param int $id
     * @return MaintenanceCategory|null
     */
    public function findMaintenanceCategoryById(int $id)
    {
        return $this->maintenanceCategoryRepo->findMaintenanceCategoryById($id);
    }


    /**
     * @param MaintenanceCategory $maintenanceCategory
     * @return Response
     */
    public function deleteMaintenanceCategory(MaintenanceCategory $maintenanceCategory)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->maintenanceCategoryRepo->deleteMaintenanceCategory($maintenanceCategory);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $maintenanceCategory, 'delete-maintenance-categories-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-maintenance-categories-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $maintenanceCategory, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function deleteMultipleMaintenanceCategories(array $ids)
    {
        //Declaration
        $result = $this->maintenanceCategoryRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
