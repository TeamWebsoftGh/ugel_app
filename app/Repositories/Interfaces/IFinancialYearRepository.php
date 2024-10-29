<?php

namespace App\Repositories\Interfaces;

use App\Models\FinancialYear;
use Illuminate\Support\Collection;

interface IFinancialYearRepository extends IBaseRepository
{
    public function updateFinancialYear(array $params, FinancialYear $financialYear);

    public function listFinancialYears(string $order = 'id', string $sort = 'desc') : Collection;

    public function createFinancialYear(array $params) : FinancialYear;

    public function createOrUpdateFinancialYear(array $data): FinancialYear;

    public function findFinancialYearById(int $id) : FinancialYear;

    public function deleteFinancialYear(FinancialYear $financialYear);

}
