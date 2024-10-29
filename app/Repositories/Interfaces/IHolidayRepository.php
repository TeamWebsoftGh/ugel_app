<?php

namespace App\Repositories\Interfaces;

use App\Models\Timesheet\Holiday;
use Illuminate\Support\Collection;

interface IHolidayRepository extends IBaseRepository
{
    public function findHolidayById(int $id);

    public function listHolidays(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createHoliday(array $params);

    public function updateHoliday(array $params, Holiday $holiday);

    public function deleteHoliday(Holiday $holiday);
}
