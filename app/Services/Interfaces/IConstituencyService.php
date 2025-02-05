<?php

namespace App\Services\Interfaces;

use App\Models\Delegate\Constituency;
use Illuminate\Support\Collection;

interface IConstituencyService extends IBaseService
{
    public function listConstituencies(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createConstituency(array $params);

    public function findConstituencyById(int $id) : Constituency;

    public function updateConstituency(array $params, Constituency $constituency);

    public function deleteConstituency(Constituency $constituency);
}
