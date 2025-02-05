<?php

namespace App\Services\Interfaces;

use App\Models\Delegate\ElectoralArea;
use Illuminate\Support\Collection;

interface IElectoralAreaService extends IBaseService
{
    public function listElectoralAreas(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createElectoralArea(array $params);

    public function findElectoralAreaById(int $id) : ElectoralArea;

    public function updateElectoralArea(array $params, ElectoralArea $electoralArea);

    public function deleteElectoralArea(ElectoralArea $electoralArea);
}
