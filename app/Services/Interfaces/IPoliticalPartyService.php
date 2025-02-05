<?php

namespace App\Services\Interfaces;

use App\Models\Election\PoliticalParty;
use Illuminate\Support\Collection;

interface IPoliticalPartyService extends IBaseService
{
    public function listPoliticalParties(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createPoliticalParty(array $params);

    public function findPoliticalPartyById(int $id) : PoliticalParty;

    public function updatePoliticalParty(array $params, PoliticalParty $politicalParty);

    public function deletePoliticalParty(PoliticalParty $politicalParty);
}
