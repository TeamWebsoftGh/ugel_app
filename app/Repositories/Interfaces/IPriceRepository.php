<?php

namespace App\Repositories\Interfaces;

use App\Models\Price;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface IPriceRepository extends IBaseRepository
{
    public function createPrice(array $attributes);

    public function findPriceById(int $id);

    public function findPrice(array $data);

    public function listPrices(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function updatePrice(array $params, Price $price);

    public function deletePrice(Price $price);
}
