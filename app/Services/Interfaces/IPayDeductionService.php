<?php

namespace App\Services\Interfaces;

use App\Models\PayDeduction;
use Illuminate\Support\Collection;

interface IPayDeductionService extends IBaseService
{
    public function listPayDeductions(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listPayDeductionsDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createPayDeduction(array $params);

    public function updatePayDeduction(array $params, PayDeduction $payDeduction);

    public function findPayDeductionById(int $id);

    public function deletePayDeduction(PayDeduction $payDeduction);

    public function createUpdatePayDeductionDetails(array $data, PayDeduction $payDeduction);

    public function deletePayDeductionDetail(int $id);
}
