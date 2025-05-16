<?php

namespace App\Services\Auth\Interfaces;

use App\Models\Auth\Permission;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IPermissionService extends IBaseService
{
    public function createPermission(array $data);

    public function listPermissions(string $order = 'id', string $sort = 'desc') : Collection;

    public function findPermissionById(int $id);

    public function updatePermission(array $data, Permission $permission);

    public function deletePermission(Permission $permission);

}
