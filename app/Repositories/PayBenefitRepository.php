<?php

namespace App\Repositories;

use App\Models\PayBenefit;
use App\Repositories\Interfaces\IPayBenefitRepository;
use Illuminate\Support\Collection;

class PayBenefitRepository extends BaseRepository implements IPayBenefitRepository
{
    /**
     * PayBenefit Repository
     *
     * @param PayBenefit $payBenefit
     */
    public function __construct(PayBenefit $payBenefit)
    {
        parent::__construct($payBenefit);
        $this->model = $payBenefit;
    }

    /**
     * List all PayBenefits
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listPayBenefits(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return PayBenefit
     */
    public function createPayBenefit(array $data): PayBenefit
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return PayBenefit
     */
    public function findPayBenefitById(int $id): PayBenefit
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
    public function updatePayBenefit(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deletePayBenefit(int $id): bool
    {
        return $this->delete($id);
    }
}
