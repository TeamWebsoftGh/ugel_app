<?php

namespace App\Services\Interfaces;

use App\Models\Auth\User;
use Illuminate\Support\Collection;

interface IUserService extends IBaseService
{
    public function listUsers(string $order = 'id', string $sort = 'desc'): Collection;

    public function createUser(array $params);

    public function findUserById(int $id) : User;

    public function updateUser(array $params, User $user);

    public function changePassword(array $params, User $user);

    public function listRoles() : Collection;

    public function changeStatus(bool $status, User $user);

    public function resetPassword(User $user);

    public function bulkPasswordReset();

    public function deleteUser(User $user);

    public function getCreateUser(array $request);

    public function sendOtp(User $user, $phone_number=null);

    public function verifyOtp(User $user, string $otp);

}
