<?php

namespace App\Repositories;

use App\Models\Election\ParliamentaryCandidate;
use App\Repositories\Interfaces\IParliamentaryCandidateRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ParliamentaryCandidateRepository extends BaseRepository implements IParliamentaryCandidateRepository
{
    /**
     * ParliamentaryCandidateRepository constructor.
     *
     * @param ParliamentaryCandidate $parliamentaryCandidate
     */
    public function __construct(ParliamentaryCandidate $parliamentaryCandidate)
    {
        parent::__construct($parliamentaryCandidate);
        $this->model = $parliamentaryCandidate;
    }

    /**
     * List all the Parliamentary Candidates
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $parliamentaryCandidates
     */
    public function listParliamentaryCandidates(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        if (!empty($filter['filter_political_party']))
        {
            $result = $result->where('political_party_id', $filter['filter_political_party']);
        }

        if (!empty($filter['filter_constituency']))
        {
            $result = $result->where('constituency_id', $filter['filter_constituency']);
        }

        if (!empty($filter['filter_type']))
        {
            $result = $result->where('type', $filter['filter_type']);
        }
        if (!empty($filter['filter_name'])) {
            // Split the name filter into parts
            $nameParts = explode(' ', $filter['filter_name']);

            $result = $result->where(function ($query) use ($nameParts) {
                foreach ($nameParts as $part) {
                    $query->orWhere('first_name', 'like', "%$part%")
                        ->orWhere('last_name', 'like', "%$part%")
                        ->orWhere('other_names', 'like', "%$part%");
                }
            });
        }
        if (!empty($filter['filter_election']))
        {
            $result = $result->where('election_id', $filter['filter_election']);
        }

        if (!empty($params['filter_region']))
        {
            $result = $result->whereHas('constituency', function ($query) use($filter) {
                return $query->where('region_id', '=', $filter['filter_region']);
            });
        }
        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Parliamentary Candidate
     *
     * @param array $params
     *
     * @return ParliamentaryCandidate
     */
    public function createParliamentaryCandidate(array $params): ParliamentaryCandidate
    {
        return $this->create($params);
    }

    /**
     * Find the Parliamentary Candidate by id
     *
     * @param int $id
     *
     * @return ParliamentaryCandidate
     */
    public function findParliamentaryCandidateById(int $id): ParliamentaryCandidate
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Parliamentary Candidate
     *
     * @param array $params
     *
     * @param ParliamentaryCandidate $parliamentaryCandidate
     * @return bool
     */
    public function updateParliamentaryCandidate(array $params, ParliamentaryCandidate $parliamentaryCandidate): bool
    {
        return $this->update($params, $parliamentaryCandidate->id);
    }

    /**
     * @param ParliamentaryCandidate $parliamentaryCandidate
     * @return bool|null
     * @throws \Exception
     */
    public function deleteParliamentaryCandidate(ParliamentaryCandidate $parliamentaryCandidate)
    {
        return $this->delete($parliamentaryCandidate->id);
    }
}
