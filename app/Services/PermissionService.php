<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\Permission;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Services\Interfaces\IPermissionService;
use Illuminate\Support\Collection;

class PermissionService extends ServiceBase implements IPermissionService
{
    private IPermissionRepository $permissionRepo;

    /**
     * UserService constructor.
     *
     * @param IPermissionRepository $permissionRepository
     */
    public function __construct(IPermissionRepository $permissionRepository)
    {
        parent::__construct();
        $this->permissionRepo = $permissionRepository;
    }

    /**
     * List all the Application Users
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listPermissions(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->permissionRepo->listPermissions();
    }


    /**
     * Create Role
     *
     * @param array $data
     * @return Helpers\Response
     */
    public function createPermission(array $data): Helpers\Response
    {
        //Declaration
        $permission = null;

        //Process Request
        try {
            $permission = $this->permissionRepo->createPermission($data);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Permission(), 'create-permission-failed');
        }

        //Check if permission was created successfully
        if (!$permission)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-permission-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $permission, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $permission;

        return $this->response;
    }

    /**
     * Find the Role by id
     *
     * @param int $id
     *
     * @return Permission
     */
    public function findPermissionById(int $id)
    {
        return $this->permissionRepo->findPermissionById($id);
    }

    /**
     * Update Role
     *
     * @param array $data
     *
     * @param int $id
     * @return Helpers\Response
     */
    public function updatePermission(array $data, Permission $permission): Helpers\Response
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->permissionRepo->updatePermission($data, $permission->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $permission, 'update-permission-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-permission-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $permission, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    public function deletePermission(Permission $permission)
    {
        //Declaration
        $result = $this->permissionRepo->delete($permission->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-permission-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $permission, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

}
