<?php

namespace App\Repositories;

use App\Models\Settings\Currency;
use App\Repositories\Interfaces\ICurrencyRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CurrencyRepository extends BaseRepository implements ICurrencyRepository
{
    Public function __construct(Currency $currency)
    {
        parent::__construct($currency);
        $this->model = $currency;
    }

    public function listCurrencies(string $order = 'id', string $sort = 'desc'):Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * @param int $id
     * @return Currency
     * @throws ModelNotFoundException
     */
    public function findCurrencyById(int $id) : Currency
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Currency
     */
    public function createCurrency(array $params) : Currency
    {
        $currency = new Currency($params);
        $currency->save();

        return $currency;
    }


    /**
     * @param array $params
     * @param Currency $currency
     * @return bool
     */
    public function updateCurrency(array $params, Currency $currency) : bool
    {
        return $this->update($params, $currency->id);
    }

    /**
     * @param Currency $currency
     * @return mixed
     */
    public function deleteCurrency(Currency $currency)
    {
        return $this->delete($currency->id);
    }
}
