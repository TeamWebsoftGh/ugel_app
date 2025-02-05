<?php

namespace App\Repositories;

use App\Models\FinancialYear;
use App\Repositories\Interfaces\IFinancialYearRepository;
use Illuminate\Support\Collection;

class FinancialYearRepository extends BaseRepository implements IFinancialYearRepository
{
    /**
     * FinancialYearRepository constructor.
     * @param FinancialYear $financialYear
     */
    public function __construct(FinancialYear $financialYear)
    {
        parent::__construct($financialYear);
        $this->model = $financialYear;
    }


    /**
     * List all FinancialYears
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listFinancialYears(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return FinancialYear
     */
    public function createFinancialYear(array $data): FinancialYear
    {
        return $this->create($data);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return FinancialYear
     */
    public function createOrUpdateFinancialYear(array $data): FinancialYear
    {
        return $this->model->updateOrCreate(
            ['year' => $data['year']],
            $data
        );
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return FinancialYear
     */
    public function findFinancialYearById(int $id): FinancialYear
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param FinancialYear $financialYear
     * @return bool
     */
    public function updateFinancialYear(array $params, FinancialYear $financialYear): bool
    {
        return $this->update($params, $financialYear->id);
    }


    /**
     * @param FinancialYear $financialYear
     * @return bool
     */
    public function deleteFinancialYear(FinancialYear $financialYear)
    {
        return $this->delete($financialYear->id);
    }
}
