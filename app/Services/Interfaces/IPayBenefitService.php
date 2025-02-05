<?php

namespace App\Services\Interfaces;

use App\Models\PayBenefit;
use Illuminate\Support\Collection;

interface IPayBenefitService extends IBaseService
{
    public function listPayBenefits(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listPayBenefitsDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createPayBenefit(array $params);

    public function updatePayBenefit(array $params, PayBenefit $payBenefit);

    public function findPayBenefitById(int $id);

    public function deletePayBenefit(PayBenefit $payBenefit);

    public function createUpdatePayBenefitDetails(array $data, PayBenefit $payBenefit);

    public function deletePayBenefitDetail(int $id);
}
