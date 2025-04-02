<?php

namespace App\Repositories\Legal\Interfaces;

use App\Repositories\Interfaces\IBaseRepository;

interface ICourtHearingRepository extends IBaseRepository
{
    public function listCourtHearings(array $filter =[], string $order = 'updated_at', string $sort = 'desc');
}
