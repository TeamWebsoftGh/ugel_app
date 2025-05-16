<?php

namespace App\Services\Legal\Interfaces;

use App\Models\Legal\CourtCase;
use App\Services\Interfaces\IBaseService;

interface ICourtCaseService extends IBaseService
{
    public function listCourtCases(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createCourtCase(array $params);

    public function findCourtCaseById(int $id) : CourtCase;

    public function updateCourtCase(array $params, CourtCase $courtCase);

    public function deleteCourtCase(CourtCase $courtCase);

    public function deleteMultipleCourtCases(array $ids);
}
