<?php

namespace App\Services\Interfaces;

use App\Models\Timesheet\Holiday;
use Illuminate\Support\Collection;

interface IHolidayService extends IBaseService
{
    public function listHolidays(array $filter = null, string $order = 'id', string $sort = 'desc'): Collection;

    public function createHoliday(array $params);

    public function findHolidayById(int $id);

    public function findHolidayByStaffId(string $staff_id);

    public function updateHoliday(array $params, Holiday $holiday);

    public function deleteHoliday(Holiday $holiday);
}
