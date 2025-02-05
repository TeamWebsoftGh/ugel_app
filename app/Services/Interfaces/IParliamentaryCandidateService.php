<?php

namespace App\Services\Interfaces;

use App\Models\Election\ParliamentaryCandidate;
use Illuminate\Support\Collection;

interface IParliamentaryCandidateService extends IBaseService
{
    public function listParliamentaryCandidates(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createParliamentaryCandidate(array $params);

    public function findParliamentaryCandidateById(int $id) : ParliamentaryCandidate;

    public function updateParliamentaryCandidate(array $params, ParliamentaryCandidate $parliamentaryCandidate);

    public function deleteParliamentaryCandidate(ParliamentaryCandidate $parliamentaryCandidate);
}
