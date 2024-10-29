<?php

namespace App\Repositories\Interfaces;

use App\Models\Settings\Currency;
use Illuminate\Support\Collection;

interface ICurrencyRepository extends IBaseRepository
{
    public function updateCurrency(array $params, Currency $currency);

    public function deleteCurrency(Currency $currency);

    public function listCurrencies(string $order = 'id', string $sort = 'desc') : Collection;

    public function createCurrency(array $params) : Currency;

    public function findCurrencyById(int $id) : Currency;
}
