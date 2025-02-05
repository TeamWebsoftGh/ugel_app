<?php

namespace App\Repositories\Interfaces;

use App\Models\Election\ElectionResult;
use Illuminate\Support\Collection;

interface IElectionResultRepository extends IBaseRepository
{
    /**
     * Fetch all election results.
     *
     * @param array|null $filter Filters to apply
     * @param string $order Column to sort by
     * @param string $sort Direction of sort (asc or desc)
     * @return Collection
     */
    public function listElectionResults(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;


    public function getTotalVotes(array $filters): \Illuminate\Database\Eloquent\Collection;

    public function getTotalVotesByRegion(array $filters): \Illuminate\Database\Eloquent\Collection;

    public function getTotalVotesByConstituency(array $filters): \Illuminate\Database\Eloquent\Collection;

    public function getTotalVotesByElectoralArea(array $filters): \Illuminate\Database\Eloquent\Collection;

    public function getTotalVotesByPollingStation(array $filters): \Illuminate\Database\Eloquent\Collection;

    public function getPollingStationsWithNoVotes(int $electionId, array $filters = []): \Illuminate\Database\Eloquent\Collection;

    public function getPollingStationVoteSummary(int $electionId);

    /**
     * Find an election result by its ID.
     *
     * @param int $id The ID of the election result
     * @return ElectionResult|null
     */
    public function findElectionResultById(int $id): ?ElectionResult;

    /**
     * Create a new election result.
     *
     * @param array $data Data to create a new election result
     * @return ElectionResult
     */
    public function createElectionResult(array $data): ElectionResult;

    /**
     * Update an existing election result.
     *
     * @param array $data Data for updating the election result
     * @param ElectionResult $electionResult The election result to update
     * @return bool
     */
    public function updateElectionResult(array $data, ElectionResult $electionResult): bool;

    /**
     * Delete an election result.
     *
     * @param ElectionResult $electionResult The election result to delete
     * @return bool
     */
    public function deleteElectionResult(ElectionResult $electionResult): bool;
}
