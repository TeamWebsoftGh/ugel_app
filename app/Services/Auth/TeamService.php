<?php

namespace App\Services\Auth;

use App\Models\Auth\Team;
use App\Repositories\Auth\Interfaces\ITeamRepository;
use App\Services\Auth\Interfaces\ITeamService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;

class TeamService extends ServiceBase implements ITeamService
{
    private ITeamRepository $teamRepo;

    /**
     * TeamService constructor.
     *
     * @param ITeamRepository $teamRepository
     */
    public function __construct(ITeamRepository $teamRepository){
        parent::__construct();
        $this->teamRepo = $teamRepository;
    }

    /**
     * List all the Teams
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listTeams(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->teamRepo->listTeams($filter, $order, $sort);
    }

    /**
     * Create Team
     *
     * @param array $params
     *
     * @return Response
     */
    public function createTeam(array $params)
    {
        $team = $this->teamRepo->create($params);
        return $this->buildCreateResponse($team);
    }


    /**
     * Find the Team by id
     *
     * @param int $id
     *
     * @return
     */
    public function findTeamById(int $id)
    {
        return $this->teamRepo->findOneOrFail($id);
    }


    /**
     * Update Team
     *
     * @param array $params
     *
     * @param Team $team
     * @return Response
     */
    public function updateTeam(array $params, Team $team)
    {
        $result = $this->teamRepo->update($params, $team->id);
        return $this->buildUpdateResponse($team, $result);
    }

    /**
     * @param Team $team
     * @return Response
     */
    public function deleteTeam(Team $team)
    {
        //Declaration
        $result = $this->teamRepo->delete($team->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleTeams(array $ids)
    {
        //Declaration
        $result = $this->teamRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
