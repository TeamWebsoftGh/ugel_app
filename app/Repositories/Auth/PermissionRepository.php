<?php

namespace App\Repositories\Auth;

use App\Models\Auth\Permission;
use App\Repositories\Auth\Interfaces\IPermissionRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class PermissionRepository extends BaseRepository implements IPermissionRepository
{
    /**
     * PermissionRepository constructor.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
        $this->model = $permission;
    }

    /**
     * @param array $data
     *
     * @return Permission
     */
    public function createPermission(array $data) : Permission
    {
        return $this->create($data);
    }

    /**
     * @param int $id
     *
     * @return Permission
     */
    public function findPermissionById(int $id) : Permission
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     */
    public function updatePermission(array $data, int $id) : bool
    {
        return $this->update($data, $id);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deletePermissionById(int $id) : bool
    {
        return $this->delete($id);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPermissions($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }
}
