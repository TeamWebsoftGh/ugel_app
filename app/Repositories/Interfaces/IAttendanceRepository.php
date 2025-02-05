<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Absence;
use App\Models\Timesheet\EmployeeAbsence;
use Illuminate\Support\Collection;

interface IAttendanceRepository extends IBaseRepository
{
    public function findAbsenceById(int $id);

    public function listAbsences(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createAbsence(array $params);

    public function updateAbsence(array $params, EmployeeAbsence $absence);

    public function deleteAbsence(EmployeeAbsence $absence);
}
