<?php

namespace App\Services\Interfaces;

use App\Models\Election\ElectionResult;
use Illuminate\Support\Collection;

interface IElectionResultService extends IBaseService
{
    /**
     * List all election results.
     *
     * @param array|null $filter Filters to apply
     * @param string $order Column to sort by
     * @param string $sort Direction of sort (asc or desc)
     * @return Collection
     */
    public function listElectionResults(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function totalVotes(array $filter = null): Collection;

    public function totalVotesByRegion(array $filter = null): Collection;

    public function totalVotesByConstituency(array $filter = null);

    public function totalVotesByElectoralArea(array $filter = null);

    public function totalVotesByPollingStation(array $filter = null);

    public function pollingStationsWithNoVotes(array $filters = []);

    public function pollingStationVoteSummary(array $filters = []);

    /**
     * Create a new election result.
     *
     * @param array $params Data for creating a new election result
     * @return
     */
    public function createElectionResult(array $params);

    /**
     * Find an election result by its ID.
     *
     * @param int $id The ID of the election result
     * @return ElectionResult
     */
    public function findElectionResultById(int $id) : ElectionResult;

    /**
     * Update an election result.
     *
     * @param array $params Parameters for updating the election result
     * @param ElectionResult $electionResult The election result to update
     */
    public function updateElectionResult(array $params, ElectionResult $electionResult);

    /**
     * Delete an election result.
     *
     * @param ElectionResult $electionResult The election result to delete
     */
    public function deleteElectionResult(ElectionResult $electionResult);
}
