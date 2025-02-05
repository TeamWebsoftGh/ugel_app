<?php

namespace App\Repositories\Interfaces;

use App\Models\Election\Election;
use Illuminate\Support\Collection;

interface IElectionRepository extends IBaseRepository
{
    public function listElections(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createElection(array $params) : Election;

    public function findElectionById(int $id) : Election;

    public function updateElection(array $params, Election $election) : bool;

    public function deleteElection(Election $election);
}
