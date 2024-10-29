<?php

namespace App\Repositories;

use App\Models\PayDeduction;
use App\Repositories\Interfaces\IPayDeductionRepository;
use Illuminate\Support\Collection;

class PayDeductionRepository extends BaseRepository implements IPayDeductionRepository
{
    /**
     * PayDeduction Repository
     *
     * @param PayDeduction $payDeduction
     */
    public function __construct(PayDeduction $payDeduction)
    {
        parent::__construct($payDeduction);
        $this->model = $payDeduction;
    }

    /**
     * List all PayDeductions
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayDeductions(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return PayDeduction
     */
    public function createPayDeduction(array $data): PayDeduction
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return PayDeduction
     */
    public function findPayDeductionById(int $id): PayDeduction
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
    public function updatePayDeduction(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deletePayDeduction(int $id): bool
    {
        return $this->delete($id);
    }
}
