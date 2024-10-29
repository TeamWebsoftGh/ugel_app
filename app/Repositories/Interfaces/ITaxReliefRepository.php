<?php

namespace App\Repositories\Interfaces;

use App\Models\TaxRelief;
use Illuminate\Support\Collection;

interface ITaxReliefRepository extends IBaseRepository
{
    public function listTaxReliefs(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createTaxRelief(array $params) : TaxRelief;

    public function findTaxReliefById(int $id) : TaxRelief;

    public function updateTaxRelief(array $params, int $id) : bool;

    public function deleteTaxRelief(int $id);
}
