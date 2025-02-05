<?php

namespace App\Repositories;

use App\Models\Organization\Company;
use App\Repositories\Interfaces\ICompanyRepository;
use Illuminate\Support\Collection;

class CompanyRepository extends BaseRepository implements ICompanyRepository
{
    /**
     * Company Repository
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        parent::__construct($company);
        $this->model = $company;
    }

    /**
     * List all Companies
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listCompanies(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the Company
     *
     * @param array $data
     *
     * @return Company
     */
    public function createCompany(array $data): Company
    {
        return $this->create($data);
    }


    /**
     * Find the Company by id
     *
     * @param int $id
     *
     * @return Company
     */
    public function findCompanyById(int $id): Company
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Company
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateCompany(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteCompany(int $id): bool
    {
        return $this->delete($id);
    }
}
