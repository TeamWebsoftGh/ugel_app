<?php

namespace App\Repositories;


use App\Models\Price;
use App\Repositories\Interfaces\IPriceRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PriceRepository extends BaseRepository implements IPriceRepository
{
    /**
     * PriceRepository constructor.
     *
     * @param Price $price
     */
    public function __construct(Price $price)
    {
        parent::__construct($price);
        $this->model = $price;
    }

    /**
     * @param array $data
     *
     * @return Price
     */
    public function createPrice(array $data) : Price
    {
        return $this->create($data);
    }

    /**
     * @param int $id
     *
     * @return Price
     * @throws ModelNotFoundException
     */
    public function findPriceById(int $id) : Price
    {
        return $this->findOneOrFail($id);
    }

    /**
     * @param array $data
     *
     * @return Price
     * @throws ModelNotFoundException
     */
    public function findPrice(array $data) : Price
    {
        return $this->findOneByOrFail($data);
    }

    /**
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updatePrice(array $params, Price $price)
    {
        return $price->update($params);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listPrices(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $orderBy, $sortBy);
    }


    public function deletePrice(Price $price)
    {
        return $this->delete($price->id);
    }
}
