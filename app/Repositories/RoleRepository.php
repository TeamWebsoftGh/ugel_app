<?php

namespace App\Repositories;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Repositories\Interfaces\IRoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    /**
     * @var Role
     */
    protected $model;
    /**
     * RoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct($role);
        $this->model = $role;
    }
    /**
     * List all Roles
     *
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listRoles(string $order = 'id', string $sort = 'desc') : Collection
    {
        return $this->all(['*'], $order, $sort)->except(1);
    }
    /**
     * @param array $data
     * @return Role
     * @throws QueryException
     */
    public function createRole(array $data) : Role
    {
        $role = new Role($data);
        $role->save();
        return $role;
    }

    /**
     * @param int $id
     *
     * @return Role
     * @throws QueryException
     */
    public function findRoleById(int $id) : Role
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     * @throws QueryException
     */
    public function updateRole(array $data, int $id) : bool
    {
        return $this->update($data, $id);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws QueryException
     */
    public function deleteRoleById(int $id) : bool
    {
        return $this->delete($id);
    }

    /**
     * @param Permission $permission
     */
    public function attachToPermission(Permission $permission)
    {
        $this->model->attachPermission($permission);
    }

    /**
     * @param Permission ...$permissions
     */
    public function attachToPermissions(... $permissions)
    {
        $this->model->attachPermissions($permissions);
    }

    /**
     * @param array $ids
     */
    public function syncPermissions(array $ids)
    {
        $this->model->syncPermissions($ids);
    }

    /**
     * @return Collection
     */
    public function listPermissions() : Collection
    {
        return $this->model->permissions()->get();
    }
}
