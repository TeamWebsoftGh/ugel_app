<?php

namespace App\Repositories;

use App\Models\Timesheet\OfficeShift;
use App\Repositories\Interfaces\IOfficeShiftRepository;
use Illuminate\Support\Collection;

class OfficeShiftRepository extends BaseRepository implements IOfficeShiftRepository
{
    /**
     * OfficeShiftRepository constructor.
     * @param OfficeShift $officeShift
     */
    public function __construct(OfficeShift $officeShift)
    {
        parent::__construct($officeShift);
        $this->model = $officeShift;
    }


    /**
     * List all OfficeShifts
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listOfficeShifts(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        $result = $this->getFilteredList($filter);
        return $result->orderBy($order, $sort)->get($columns);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return OfficeShift
     */
    public function createOfficeShift(array $data): OfficeShift
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return OfficeShift
     */
    public function findOfficeShiftById(int $id): OfficeShift
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param OfficeShift $officeShift
     * @return bool
     */
    public function updateOfficeShift(array $params, OfficeShift $officeShift): bool
    {
        return $this->update($params, $officeShift->id);
    }


    /**
     * @param OfficeShift $officeShift
     * @return bool
     */
    public function deleteOfficeShift(OfficeShift $officeShift)
    {
        return $officeShift->delete();
    }
}
