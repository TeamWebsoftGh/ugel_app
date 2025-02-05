<?php

namespace App\Repositories\Interfaces;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Support\Collection;

interface IRoleRepository extends IBaseRepository
{
    public function createRole(array $data) : Role;

    public function listRoles(string $order = 'id', string $sort = 'desc') : Collection;

    public function findRoleById(int $id);

    public function updateRole(array $data, int $id) : bool;

    public function deleteRoleById(int $id) : bool;

    public function attachToPermission(Permission $permission);

    public function attachToPermissions(... $permissions);

    public function syncPermissions(array $ids);

    public function listPermissions() : Collection;

}
