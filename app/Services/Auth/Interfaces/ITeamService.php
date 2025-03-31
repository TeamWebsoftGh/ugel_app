<?php

namespace App\Services\Auth\Interfaces;

use App\Models\Auth\Team;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface ITeamService extends IBaseService
{
    public function listTeams(array $filter =[], string $order = 'updated_at', string $sort = 'desc');

    public function createTeam(array $params);

    public function updateTeam(array $params, Team $team);

    public function findTeamById(int $id);

    public function deleteTeam(Team $team);

    public function deleteMultipleTeams(array $ids);
}
