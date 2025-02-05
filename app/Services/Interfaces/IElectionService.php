<?php

namespace App\Services\Interfaces;

use App\Models\Election\Election;
use Illuminate\Support\Collection;

interface IElectionService extends IBaseService
{
    public function listElections(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createElection(array $params);

    public function findElectionById(int $id) : Election;

    public function updateElection(array $params, Election $election);

    public function deleteElection(Election $election);
}
