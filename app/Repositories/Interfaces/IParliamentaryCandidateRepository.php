<?php

namespace App\Repositories\Interfaces;

use App\Models\Election\ParliamentaryCandidate;
use Illuminate\Support\Collection;

interface IParliamentaryCandidateRepository extends IBaseRepository
{
    public function listParliamentaryCandidates(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createParliamentaryCandidate(array $params) : ParliamentaryCandidate;

    public function findParliamentaryCandidateById(int $id) : ParliamentaryCandidate;

    public function updateParliamentaryCandidate(array $params, ParliamentaryCandidate $parliamentaryCandidate) : bool;

    public function deleteParliamentaryCandidate(ParliamentaryCandidate $parliamentaryCandidate);
}
