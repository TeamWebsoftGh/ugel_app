<?php

namespace App\Repositories\Auth\Interfaces;

use App\Models\Auth\Team;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Support\Collection;

interface ITeamRepository extends IBaseRepository
{
    public function listTeams(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
