<?php

namespace App\Repositories;

use App\Models\TaxRelief;
use App\Repositories\Interfaces\ITaxReliefRepository;
use Illuminate\Support\Collection;

class TaxReliefRepository extends BaseRepository implements ITaxReliefRepository
{
    /**
     * TaxRelief Repository
     *
     * @param TaxRelief $taxRelief
     */
    public function __construct(TaxRelief $taxRelief)
    {
        parent::__construct($taxRelief);
        $this->model = $taxRelief;
    }

    /**
     * List all TaxReliefs
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listTaxReliefs(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return TaxRelief
     */
    public function createTaxRelief(array $data): TaxRelief
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return TaxRelief
     */
    public function findTaxReliefById(int $id): TaxRelief
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateTaxRelief(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteTaxRelief(int $id): bool
    {
        return $this->delete($id);
    }
}
