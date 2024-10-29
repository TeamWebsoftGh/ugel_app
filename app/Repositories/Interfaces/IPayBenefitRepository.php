<?php

namespace App\Repositories\Interfaces;

use App\Models\PayBenefit;
use Illuminate\Support\Collection;

interface IPayBenefitRepository extends IBaseRepository
{
    public function listPayBenefits(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createPayBenefit(array $params) : PayBenefit;

    public function findPayBenefitById(int $id) : PayBenefit;

    public function updatePayBenefit(array $params, int $id) : bool;

    public function deletePayBenefit(int $id);
}
