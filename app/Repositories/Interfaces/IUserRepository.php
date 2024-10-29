<?php

namespace App\Repositories\Interfaces;

use App\Models\Auth\User;
use Illuminate\Support\Collection;

interface IUserRepository extends IBaseRepository
{
    public function listUsers(string $order = 'id', string $sort = 'desc'): Collection;

    public function createUser(array $params) : User;

    public function findUserById(int $id) : User;

    public function updateUser(array $params, User $user) : bool;

    public function syncRoles(array $roleIds);

    public function listRoles() : Collection;

    public function hasRole(string $roleName) : bool;

    public function listPermissions(): Collection;

    public function syncPermissions(array $ids);

}
