<?php

namespace App\Services\Auth;

use App\Models\Auth\Team;
use App\Repositories\Auth\Interfaces\ITeamRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionTypeRepository;
use App\Services\Auth\Interfaces\ITeamService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TeamService extends ServiceBase implements ITeamService
{
    private ITeamRepository $teamRepo;
    private IWorkflowPositionTypeRepository $workflowPositionTypeRepo;

    /**
     * TeamService constructor.
     *
     * @param ITeamRepository $teamRepository
     */
    public function __construct(ITeamRepository $teamRepository, IWorkflowPositionTypeRepository $workflowPositionTypeRepo){
        parent::__construct();
        $this->teamRepo = $teamRepository;
        $this->workflowPositionTypeRepo = $workflowPositionTypeRepo;
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
        $params['code'] = Str::slug($params['name']);
        $team = $this->teamRepo->create($params);

        if(isset($params['assigned_users'])){
            $team->users()->sync($params['assigned_users']);
        }
        $this->workflowPositionTypeRepo->create($params);
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
        $params['code'] = Str::slug($params['name']);
        $wpt = $this->workflowPositionTypeRepo->findOneBy(['code' => $team->code]);
        $result = $this->teamRepo->update($params, $team->id);
        if(isset($params['assigned_users'])){
            $team->users()->sync($params['assigned_users']);
        }

        if($wpt)
        {
            $this->workflowPositionTypeRepo->update($params, $wpt->id);
        }else{
            $this->workflowPositionTypeRepo->create($params);
        }
        return $this->buildUpdateResponse($team, $result);
    }

    /**
     * @param Team $team
     * @return Response
     */
    public function deleteTeam(Team $team)
    {
        //Declaration
        $wpt = $this->workflowPositionTypeRepo->findOneBy(['code' => $team->code]);
        $result = $this->teamRepo->delete($team->id);
        $wpt?->delete();
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleTeams(array $ids)
    {
        //Declaration
        $result = $this->teamRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
