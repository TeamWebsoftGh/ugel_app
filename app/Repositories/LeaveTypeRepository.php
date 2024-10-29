<?php

namespace App\Repositories;

use App\Models\Timesheet\LeaveType;
use App\Repositories\Interfaces\ILeaveTypeRepository;
use Illuminate\Support\Collection;

class LeaveTypeRepository extends BaseRepository implements ILeaveTypeRepository
{
    /**
     * LeaveType Repository
     *
     * @param LeaveType $leaveType
     */
    public function __construct(LeaveType $leaveType)
    {
        parent::__construct($leaveType);
        $this->model = $leaveType;
    }

    /**
     * List all LeaveTypes
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listLeaveTypes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return LeaveType
     */
    public function createLeaveType(array $data): LeaveType
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return LeaveType
     */
    public function findLeaveTypeById(int $id): LeaveType
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
    public function updateLeaveType(array $params, int $id): bool
    {
        return $this->update($params, $id);
    }


    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteLeaveType(int $id): bool
    {
        return $this->delete($id);
    }
}
