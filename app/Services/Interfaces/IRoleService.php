<?php

namespace App\Services\Interfaces;

use App\Models\Auth\Role;
use Illuminate\Support\Collection;

interface IRoleService extends IBaseService
{
    public function createRole(array $data);

    public function listRoles(string $order = 'id', string $sort = 'desc') : Collection;

    public function findRoleById(int $id);

    public function updateRole(array $data, Role $role);

    public function deleteRole(Role $role);

    public function listPermissions() : Collection;

}
