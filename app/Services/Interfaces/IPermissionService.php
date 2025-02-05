<?php

namespace App\Services\Interfaces;

use App\Models\Auth\Permission;
use Illuminate\Support\Collection;

interface IPermissionService extends IBaseService
{
    public function createPermission(array $data);

    public function listPermissions(string $order = 'id', string $sort = 'desc') : Collection;

    public function findPermissionById(int $id);

    public function updatePermission(array $data, Permission $permission);

    public function deletePermission(Permission $permission);

}
