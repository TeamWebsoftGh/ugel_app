<?php

namespace App\Services\Interfaces;

use App\Models\Timesheet\OfficeShift;

interface IOfficeShiftService extends IBaseService
{
    public function listOfficeShifts(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']);

    public function createOfficeShift(array $params);

    public function findOfficeShiftById($id);

    public function updateOfficeShift(array $params, OfficeShift $officeShift);

    public function deleteOfficeShift(OfficeShift $officeShift);
}
