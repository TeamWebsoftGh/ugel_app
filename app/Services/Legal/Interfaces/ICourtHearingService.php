<?php

namespace App\Services\Legal\Interfaces;

use App\Models\Legal\CourtHearing;
use App\Services\Interfaces\IBaseService;

interface ICourtHearingService extends IBaseService
{
    public function listCourtHearings(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createCourtHearing(array $params);

    public function findCourtHearingById(int $id) : CourtHearing;

    public function updateCourtHearing(array $params, CourtHearing $courtHearing);

    public function deleteCourtHearing(CourtHearing $courtHearing);

    public function deleteMultipleCourtHearings(array $ids);
}
