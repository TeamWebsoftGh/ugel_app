<?php

namespace App\Repositories;

use App\Models\Timesheet\Holiday;
use App\Repositories\Interfaces\IHolidayRepository;
use Illuminate\Support\Collection;

class HolidayRepository extends BaseRepository implements IHolidayRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param Holiday $holiday
     */
    public function __construct(Holiday $holiday)
    {
        parent::__construct($holiday);
        $this->model = $holiday;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return Holiday
     */
    public function findHolidayById(int $id): Holiday
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Holiday
     */
    public function createHoliday(array $data) : Holiday
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Holiday $holiday
     * @return bool
     */
    public function updateHoliday(array $data, Holiday $holiday) : bool
    {
        return $this->update($data, $holiday->id);
    }

    /**
     * @param Holiday $holiday
     * @return bool
     */
    public function deleteHoliday(Holiday $holiday) : bool
    {
        return $this->delete($holiday->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listHolidays(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }

}
