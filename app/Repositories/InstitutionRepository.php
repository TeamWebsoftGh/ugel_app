<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Institution;
use App\Repositories\Interfaces\ICustomerRepository;
use App\Repositories\Interfaces\IInstitutionRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class InstitutionRepository extends BaseRepository implements IInstitutionRepository
{
    /**
     * Institution Repository
     *
     * @param Institution $institution
     */
    public function __construct(Institution $institution)
    {
        parent::__construct($institution);
        $this->model = $institution;
    }

    /**
     * List all Institutions
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listInstitution(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        if (user('admin')->hasRole('super-admin|admin'))
            return $this->all($columns, $order, $sort);

        return $this->all($columns, $order, $sort)->except(1);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return Institution
     */
    public function createInstitution(array $data): Institution
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return Institution
     */
    public function findInstitutionById(int $id): Institution
    {
        return $this->findOneOrFail($id);
    }

    public function findInstitutionBySlug(string $slug): Institution
    {
        return $this->findOneOrFail($slug);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateInstitution(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteInstitution(int $id): bool
    {
        return $this->delete($id);
    }
}
