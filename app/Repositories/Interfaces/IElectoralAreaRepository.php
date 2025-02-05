<?php

namespace App\Repositories\Interfaces;

use App\Models\Delegate\ElectoralArea;
use Illuminate\Support\Collection;

interface IElectoralAreaRepository extends IBaseRepository
{
    public function listElectoralAreas(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createElectoralArea(array $params) : ElectoralArea;

    public function findElectoralAreaById(int $id) : ElectoralArea;

    public function updateElectoralArea(array $params, ElectoralArea $electoralArea) : bool;

    public function deleteElectoralArea(ElectoralArea $electoralArea);
}
