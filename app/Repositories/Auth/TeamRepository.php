<?php

namespace App\Repositories\Auth;

use App\Models\Auth\Team;
use App\Repositories\Auth\Interfaces\ITeamRepository;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class TeamRepository extends BaseRepository implements ITeamRepository
{
    /**
     * Team Repository
     *
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        parent::__construct($team);
        $this->model = $team;
    }

    /**
     * List all Teams
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listTeams(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->getFilteredList($filter)->orderBy($order, $sort);
    }
}
