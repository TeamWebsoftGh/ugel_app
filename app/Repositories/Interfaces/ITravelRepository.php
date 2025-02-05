<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Travel;
use Illuminate\Support\Collection;

interface ITravelRepository extends IBaseRepository
{
    public function listTravels(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createTravel(array $params) : Travel;

    public function findTravelById(int $id) : Travel;

    public function updateTravel(array $params, Travel $travel) : bool;

    public function deleteTravel(Travel $travel);

}
