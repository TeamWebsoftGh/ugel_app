<?php

namespace App\Repositories\Interfaces;

use App\Models\PayDeduction;
use Illuminate\Support\Collection;

interface IPayDeductionRepository extends IBaseRepository
{
    public function listPayDeductions(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createPayDeduction(array $params) : PayDeduction;

    public function findPayDeductionById(int $id) : PayDeduction;

    public function updatePayDeduction(array $params, int $id) : bool;

    public function deletePayDeduction(int $id);
}
