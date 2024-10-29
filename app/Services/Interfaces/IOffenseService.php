<?php

namespace App\Services\Interfaces;

use App\Models\Property\Offense;
use Illuminate\Support\Collection;

interface IOffenseService extends IBaseService
{
    public function listOffenses(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createOffense(array $params);

    public function findOffenseById(int $id);

    public function updateOffense(array $params, Offense $Offense);

    public function deleteOffense(Offense $Offense);
}
