<?php

namespace App\Repositories\Auth\Interfaces;

use App\Models\Auth\Permission;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface IPermissionRepository extends IBaseRepository
{
    public function createPermission(array $data) : Permission;

    public function findPermissionById(int $id) : Permission;

    public function updatePermission(array $data, int $id) : bool;

    public function deletePermissionById(int $id) : bool;

    public function listPermissions($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'):Collection;

}
