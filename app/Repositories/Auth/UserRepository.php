<?php

namespace App\Repositories\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements IUserRepository
{
    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $user;
    }

    /**
     * List all the Users
     *
     * @param string $order
     * @param string $sort
     *
     * @return $users
     */
    public function listUsers(string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $query = $this->getFilteredList();

        $query = $query->whereNull("client_id")->orderBy($order, $sort);

        return $query->get()
            ->except(array(1, user()->id));
    }

    /**
     * Create the User
     *
     * @param array $data
     *
     * @return User
     */
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    /**
     * Find the user by id
     *
     * @param int $id
     *
     * @return User
     */
    public function findUserById(int $id): User
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update User
     *
     * @param array $params
     *
     * @param User $user
     * @return bool
     */
    public function updateUser(array $params, User $user): bool
    {
        if (isset($params['password']))
        {
            $params['password'] = Hash::make($params['password']);
        }
        return $user->update($params);
    }

    /**
     * @param array $roleIds
     */
    public function syncRoles(array $roleIds)
    {
        $this->model->syncRoles($roleIds);
    }

    /**
     * @return Collection
     */
    public function listRoles(): Collection
    {
        return Role::all();
    }

    /**
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->model->hasRole($roleName);
    }

    /**
     * @return Collection
     */
    public function listPermissions():Collection
    {
        return $this->model->getAllPermissions();
    }

    /**
     * @param array $ids
     */
    public function syncPermissions(array $ids)
    {
        $this->model->syncPermissions($ids);
    }
}
