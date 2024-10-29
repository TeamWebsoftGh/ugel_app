<?php

namespace App\Services\Interfaces;

use App\Models\Settings\Currency;
use Illuminate\Support\Collection;

interface ICurrencyService extends IBaseService
{
    public function listCurrencies(string $order = 'id', string $sort = 'desc'): Collection;

    public function createCurrency(array $params);

    public function findCurrencyById(int $id) : Currency;

    public function updateCurrency(array $params, Currency $currency);

    public function deleteCurrency(Currency $currency);
}
