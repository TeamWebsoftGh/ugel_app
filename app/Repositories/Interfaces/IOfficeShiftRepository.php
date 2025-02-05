<?php

namespace App\Repositories\Interfaces;

use App\Models\Timesheet\OfficeShift;
use Illuminate\Support\Collection;

interface IOfficeShiftRepository extends IBaseRepository
{
    public function updateOfficeShift(array $params, OfficeShift $officeShift);

    public function listOfficeShifts(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createOfficeShift(array $params) : OfficeShift;

    public function findOfficeShiftById(int $id) : OfficeShift;

    public function deleteOfficeShift(OfficeShift $officeShift);

}
