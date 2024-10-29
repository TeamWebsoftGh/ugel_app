<?php

namespace App\Services\Interfaces;

use App\Models\PayBenefit;
use Illuminate\Support\Collection;

interface IPayRunService extends IBaseService
{
    public function listPaySummaries(int $payPeriodId, string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function runPay(array $params);

    public function reversePay(array $params);

    public function postPay(int $id);

    public function generatePaySlip(int $id);
}
