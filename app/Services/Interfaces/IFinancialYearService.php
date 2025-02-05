<?php

namespace App\Services\Interfaces;

use App\Models\FinancialYear;
use App\Models\PayPeriod;

interface IFinancialYearService extends IBaseService
{
    public function listFinancialYears();

    public function createFinancialYear(array $params);

    public function findFinancialYearById($id);

    public function payPeriods($id);

    public function updateFinancialYear(array $params, FinancialYear $financialYear);

    public function deleteFinancialYear(FinancialYear $financialYear);

    public function changePayPeriodStatus(bool $status, PayPeriod $payPeriod);
}
