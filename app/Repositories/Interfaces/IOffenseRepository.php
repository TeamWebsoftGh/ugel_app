<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Offense;
use Illuminate\Support\Collection;

interface IOffenseRepository extends IBaseRepository
{
    public function findOffenseById(int $id);

    public function listOffenses(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createOffense(array $params);

    public function updateOffense(array $params, Offense $offense);

    public function deleteOffense(Offense $offense);
}
