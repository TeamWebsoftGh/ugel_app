<?php

namespace App\Services\Interfaces;

use App\Models\TaxRelief;
use Illuminate\Support\Collection;

interface ITaxReliefService extends IBaseService
{
    public function listTaxReliefs(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function listTaxReliefDetails($id, string $order = 'id', string $sort = 'desc', $columns = ['*']): Collection;

    public function createTaxRelief(array $params);

    public function updateTaxRelief(array $params, TaxRelief $taxRelief);

    public function findTaxReliefById(int $id);

    public function deleteTaxRelief(TaxRelief $taxRelief);

    public function createUpdateTaxReliefDetails(array $data, TaxRelief $taxRelief);

    public function deleteTaxReliefDetail(int $id);
}
