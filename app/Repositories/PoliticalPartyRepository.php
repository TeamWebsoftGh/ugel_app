<?php

namespace App\Repositories;

use App\Models\Election\PoliticalParty;
use App\Repositories\Interfaces\IPoliticalPartyRepository;
use Illuminate\Support\Collection;

class PoliticalPartyRepository extends BaseRepository implements IPoliticalPartyRepository
{
    /**
     * PoliticalPartyRepository constructor.
     *
     * @param PoliticalParty $politicalParty
     */
    public function __construct(PoliticalParty $politicalParty)
    {
        parent::__construct($politicalParty);
        $this->model = $politicalParty;
    }

    /**
     * List all the Political Parties
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $politicalParties
     */
    public function listPoliticalParties(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Political Party
     *
     * @param array $data
     *
     * @return PoliticalParty
     */
    public function createPoliticalParty(array $data): PoliticalParty
    {
        return $this->create($data);
    }

    /**
     * Find the Political Party by id
     *
     * @param int $id
     *
     * @return PoliticalParty
     */
    public function findPoliticalPartyById(int $id): PoliticalParty
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Political Party
     *
     * @param array $data
     *
     * @param PoliticalParty $politicalParty
     * @return bool
     */
    public function updatePoliticalParty(array $data, PoliticalParty $politicalParty): bool
    {
        return $this->update($data, $politicalParty->id);
    }

    /**
     * Delete Political Party
     *
     * @param PoliticalParty $politicalParty
     * @return bool
     * @throws \Exception
     */
    public function deletePoliticalParty(PoliticalParty $politicalParty): bool
    {
        return $this->delete($politicalParty->id);
    }
}
