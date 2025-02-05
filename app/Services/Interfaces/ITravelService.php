<?php

namespace App\Services\Interfaces;

use App\Models\Property\Travel;
use Illuminate\Support\Collection;

interface ITravelService extends IBaseService
{
    public function listTravels(array $filter, string $order = 'id', string $sort = 'desc') : Collection;

    public function createTravel(array $params);

    public function updateTravel(array $params, Travel $travel);

    public function findTravelById(int $id);

    public function deleteTravel(Travel $travel);
}
