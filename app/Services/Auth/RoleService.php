<?php

namespace App\Services\Auth;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\Role;
use App\Repositories\Auth\Interfaces\IRoleRepository;
use App\Repositories\Auth\RoleRepository;
use App\Services\Auth\Interfaces\IRoleService;
use App\Services\Helpers;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;

class RoleService extends ServiceBase implements IRoleService
{

    private $roleRepo;

    /**
     * RoleService constructor.
     *
     * @param IRoleRepository $roleRepository
     */
    public function __construct(IRoleRepository $roleRepository)
    {
        parent::__construct();
        $this->roleRepo = $roleRepository;
    }

    /**
     * List all the Application Users
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listRoles(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->roleRepo->listRoles();
    }


    /**
     * Create Role
     *
     * @param array $data
     * @return Helpers\Response
     */
    public function createRole(array $data): Helpers\Response
    {
        //Declaration
        $role = null;

        //Process Request
        try {
            $role = $this->roleRepo->createRole($data);
            //Add Permissions
            $roleRepo = new RoleRepository($role);
            if (isset($data['permissions']))
                $roleRepo->syncPermissions($data['permissions']);

        } catch (\Exception $e) {
            log_error(format_exception($e), new Role(), 'create-role-failed');
        }

        //Check if Role was created successfully
        if (!$role)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-role-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $role, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $role;

        return $this->response;
    }

    /**
     * Find the Role by id
     *
     * @param int $id
     *
     * @return array
     */
    public function findRoleById(int $id)
    {
        return $this->roleRepo->findRoleById($id);
    }

    /**
     * Update Role
     *
     * @param array $data
     *
     * @param int $id
     * @return Helpers\Response
     */
    public function updateRole(array $data, Role $role): Helpers\Response
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->roleRepo->updateRole($data, $role->id);
            //Add Permissions
            $roleRepo = new RoleRepository($role);
            if (isset($data['permissions']))
                $roleRepo->syncPermissions($data['permissions']);

        } catch (\Exception $e) {
            log_error(format_exception($e), $role, 'update-role-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-role-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $role, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @return Collection
     */
    public function listPermissions():Collection
    {
        return $this->roleRepo->listPermissions();
    }


    public function deleteRole(Role $role)
    {
        //Declaration
        $result = $this->roleRepo->delete($role->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-role-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $role, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

}
