<?php

namespace App\Repositories\Interfaces;

use App\Models\Election\PoliticalParty;
use Illuminate\Support\Collection;

interface IPoliticalPartyRepository extends IBaseRepository
{
    /**
     * Fetch all political parties.
     *
     * @param array|null $filter Filters to apply
     * @param string $order Column to sort by
     * @param string $sort Direction of sort (asc or desc)
     * @return Collection
     */
    public function listPoliticalParties(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    /**
     * Find a political party by its ID.
     *
     * @param int $id The ID of the political party
     * @return PoliticalParty|null
     */
    public function findPoliticalPartyById(int $id): ?PoliticalParty;

    /**
     * Create a new political party.
     *
     * @param array $data Data to create a new political party
     * @return PoliticalParty
     */
    public function createPoliticalParty(array $data): PoliticalParty;

    /**
     * Update an existing political party.
     *
     * @param array $data Data for updating the political party
     * @param PoliticalParty $politicalParty The political party to update
     * @return bool
     */
    public function updatePoliticalParty(array $data, PoliticalParty $politicalParty): bool;

    /**
     * Delete a political party.
     *
     * @param PoliticalParty $politicalParty The political party to delete
     * @return bool
     */
    public function deletePoliticalParty(PoliticalParty $politicalParty): bool;
}
