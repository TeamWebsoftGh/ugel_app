<?php

namespace App\Repositories;

use App\Models\Timesheet\LeaveSchedule;
use App\Repositories\Interfaces\ILeaveScheduleRepository;
use Illuminate\Support\Collection;

class LeaveScheduleRepository extends BaseRepository implements ILeaveScheduleRepository
{
    /**
     * LeaveSchedule Repository
     *
     * @param LeaveSchedule $leaveSchedule
     */
    public function __construct(LeaveSchedule $leaveSchedule)
    {
        parent::__construct($leaveSchedule);
        $this->model = $leaveSchedule;
    }

    /**
     * List all LeaveSchedules
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveSchedules(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
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
     * @return LeaveSchedule
     */
    public function createLeaveSchedule(array $params): LeaveSchedule
    {
        return $this->create($params);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return LeaveSchedule
     */
    public function findLeaveScheduleById(int $id): LeaveSchedule
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
    public function updateLeaveSchedule(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteLeaveSchedule(int $id): bool
    {
        return $this->delete($id);
    }
}
