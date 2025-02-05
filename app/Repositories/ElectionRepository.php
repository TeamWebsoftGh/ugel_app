<?php

namespace App\Repositories;

use App\Models\Election\Election;
use App\Repositories\Interfaces\IElectionRepository;
use Illuminate\Support\Collection;

class ElectionRepository extends BaseRepository implements IElectionRepository
{
    /**
     * ElectionRepository constructor.
     *
     * @param Election $election
     */
    public function __construct(Election $election)
    {
        parent::__construct($election);
        $this->model = $election;
    }

    /**
     * List all the Elections
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $elections
     */
    public function listElections(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Election
     *
     * @param array $data
     *
     * @return Election
     */
    public function createElection(array $data): Election
    {
        return $this->create($data);
    }

    /**
     * Find the Election by id
     *
     * @param int $id
     *
     * @return Election
     */
    public function findElectionById(int $id): Election
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Election
     *
     * @param array $params
     *
     * @param Election $election
     * @return bool
     */
    public function updateElection(array $params, Election $election): bool
    {
        return $this->update($params, $election->id);
    }

    /**
     * @param Election $election
     * @return bool|null
     * @throws \Exception
     */
    public function deleteElection(Election $election)
    {
        return $election->delete();
    }
}
