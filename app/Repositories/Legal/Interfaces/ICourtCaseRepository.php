<?php

namespace App\Repositories\Legal\Interfaces;

use App\Repositories\Interfaces\IBaseRepository;

interface ICourtCaseRepository extends IBaseRepository
{
    public function listCourtCases(array $filter =[], string $order = 'updated_at', string $sort = 'desc');
}
