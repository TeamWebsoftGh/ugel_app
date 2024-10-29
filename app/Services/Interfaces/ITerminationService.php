<?php

namespace App\Services\Interfaces;

use App\Models\Property\Termination;
use Illuminate\Support\Collection;

interface ITerminationService extends IBaseService
{
    public function listTerminations(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createTermination(array $params);

    public function findTerminationById(int $id);

    public function findTerminationByStaffId(string $staff_id);

    public function updateTermination(array $params, Termination $termination);

    public function deleteTermination(Termination $termination);
}
