<?php

namespace App\Services\Auth\Interfaces;

use App\Models\Auth\User;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IUserService extends IBaseService
{
    public function listUsers(string $order = 'id', string $sort = 'desc'): Collection;

    public function createUser(array $data);

    public function findUserById(int $id) : User;

    public function updateUser(array $data, User $user);

    public function changePassword(array $data, User $user);

    public function listRoles() : Collection;

    public function changeStatus(bool $status, User $user);

    public function resetPassword(User $user);

    public function bulkPasswordReset();

    public function deleteUser(User $user);

    public function getCreateUser(array $request);

    public function sendOtp(User $user, $phone_number=null);

    public function verifyOtp(User $user, string $otp);

}
