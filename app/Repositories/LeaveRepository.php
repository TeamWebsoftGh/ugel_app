<?php

namespace App\Repositories;

use App\Models\Timesheet\Leave;
use App\Models\Timesheet\LeaveBalance;
use App\Repositories\Interfaces\ILeaveRepository;
use Illuminate\Support\Collection;

class LeaveRepository extends BaseRepository implements ILeaveRepository
{
    /**
     * Leave Repository
     *
     * @param Leave $leave
     */
    public function __construct(Leave $leave)
    {
        parent::__construct($leave);
        $this->model = $leave;
    }

    /**
     * List all Leaves
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaves(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        $result = $this->getFilteredList($filter);

        if (!empty($filter['filter_leave_type']))
        {
            $result = $result->where('leave_type_id', $filter['filter_leave_type']);
        }

        if (!empty($filter['filter_leave_year']))
        {
            $result = $result->where('leave_year', $filter['filter_leave_year']);
        }

        return $result->orderBy($order, $sort)->get($columns);
    }

    /**
     * Create the appUser
     *
     * @param array $params
     *
     * @return Leave
     */
    public function createLeave(array $params): Leave
    {
        return $this->create($params);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return Leave
     */
    public function findLeaveById(int $id): Leave
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param int $id
     *
     * @return bool
     */
    public function updateLeave(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteLeave(int $id): bool
    {
        return $this->delete($id);
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array|string[] $columns
     * @return Collection
     */
    public function listLeaveBalances(int $leaveType, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return LeaveBalance::where('leave_type_id', $leaveType)->orderBy($order, $sort)->get($columns);
    }
}
