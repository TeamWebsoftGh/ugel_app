<?php

namespace App\Repositories;

use App\Models\Delegate\Constituency;
use App\Models\Delegate\PollingStation;
use App\Models\Election\CandidateElectionResult;
use App\Models\Election\ElectionResult;
use App\Repositories\Interfaces\IElectionResultRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ElectionResultRepository extends BaseRepository implements IElectionResultRepository
{
    /**
     * ElectionResultRepository constructor.
     *
     * @param ElectionResult $electionResult
     */
    public function __construct(ElectionResult $electionResult)
    {
        parent::__construct($electionResult);
        $this->model = $electionResult;
    }

    /**
     * List all the Election Results
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $electionResults
     */
    public function listElectionResults(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        // Filter by Polling Station
        if (!empty($filter['filter_polling_station'])) {
            $result->where('polling_station_id', $filter['filter_polling_station']);
        }

        // Filter by Electoral Area through Polling Station
        if (!empty($filter['filter_electoral_area'])) {
            $result->whereHas('polling_station', function ($query) use ($filter) {
                $query->where('electoral_area_id', $filter['filter_electoral_area']);
            });
        }

        // Filter by Constituency through Electoral Area in Polling Station
        if (!empty($filter['filter_constituency'])) {
            $result->whereHas('polling_station.electoral_area', function ($query) use ($filter) {
                $query->where('constituency_id', $filter['filter_constituency']);
            });
        }

        // Filter by Region through Constituency in Electoral Area in Polling Station
        if (!empty($filter['filter_region'])) {
            $result->whereHas('polling_station.electoral_area.constituency', function ($query) use ($filter) {
                $query->where('region_id', $filter['filter_region']);
            });
        }

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Election Result
     *
     * @param array $data
     *
     * @return ElectionResult
     */
    public function createElectionResult(array $data): ElectionResult
    {
        return $this->create($data);
    }


    public function getTotalVotes(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        // Calculate the total votes once, considering the filters
        $totalVotes = CandidateElectionResult::join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->when(isset($filters['election_id']), function ($query) use ($filters) {
                return $query->where('election_results.election_id', $filters['election_id']);
            })
            ->when(isset($filters['region_id']), function ($query) use ($filters) {
                return $query->where('regions.id', $filters['region_id']);
            })
            ->when(isset($filters['constituency_id']), function ($query) use ($filters) {
                return $query->where('constituencies.id', $filters['constituency_id']);
            })
            ->when(isset($filters['electoral_area_id']), function ($query) use ($filters) {
                return $query->where('electoral_areas.id', $filters['electoral_area_id']);
            })
            ->when(isset($filters['polling_station_id']), function ($query) use ($filters) {
                return $query->where('polling_stations.id', $filters['polling_station_id']);
            })
            ->when(isset($filters['candidate_id']), function ($query) use ($filters) {
                return $query->where('candidate_id', $filters['candidate_id']);
            })
            ->sum('candidate_election_results.votes');

        // Starting the main query
        $query = CandidateElectionResult::with('parliamentaryCandidate.political_party')
            ->join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->join('polling_stations', 'polling_stations.id', '=', 'election_results.polling_station_id')
            ->join('electoral_areas', 'electoral_areas.id', '=', 'polling_stations.electoral_area_id')
            ->join('constituencies', 'constituencies.id', '=', 'electoral_areas.constituency_id')
            ->join('regions', 'regions.id', '=', 'constituencies.region_id')
            ->join('parliamentary_candidates', 'parliamentary_candidates.id', '=', 'candidate_election_results.candidate_id')
            ->join('political_parties', 'political_parties.id', '=', 'parliamentary_candidates.political_party_id')
            ->select(
                DB::raw("CONCAT(UPPER(LEFT(parliamentary_candidates.first_name, 1)), LOWER(SUBSTRING(parliamentary_candidates.first_name, 2)), ' ', UPPER(LEFT(parliamentary_candidates.other_names, 1)), LOWER(SUBSTRING(parliamentary_candidates.other_names, 2)), ' ', UPPER(LEFT(parliamentary_candidates.last_name, 1)), LOWER(SUBSTRING(parliamentary_candidates.last_name, 2))) AS candidate_name"),
                'political_parties.name as party_name',
                'political_parties.code as party_code',
                'parliamentary_candidates.image as image_filename',  // Fetching the filename from the database
                'candidate_election_results.candidate_id',
                DB::raw("SUM(candidate_election_results.votes) as total_votes"),
                DB::raw("ROUND((SUM(candidate_election_results.votes) / $totalVotes) * 100, 2) as percentage"),
                DB::raw("CONCAT('" . asset('') . "', IF(parliamentary_candidates.image != '', CONCAT('uploads/', parliamentary_candidates.image), 'assets/images/user.png')) AS image_url")
            )
            ->groupBy('candidate_election_results.candidate_id');

        // Adding order by percentage descending
        $query->orderBy(DB::raw("ROUND((SUM(candidate_election_results.votes) / $totalVotes) * 100, 2)"), 'desc');

        // Execute the query and get results
        return $query->get();
    }


    public function getTotalVotesByRegion(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        // Starting the query with necessary joins and eager loading relationships
        $query = CandidateElectionResult::with('parliamentaryCandidate.political_party')
            ->join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->join('polling_stations', 'polling_stations.id', '=', 'election_results.polling_station_id')
            ->join('electoral_areas', 'electoral_areas.id', '=', 'polling_stations.electoral_area_id')
            ->join('constituencies', 'constituencies.id', '=', 'electoral_areas.constituency_id')
            ->join('regions', 'regions.id', '=', 'constituencies.region_id')
            ->join('parliamentary_candidates', 'parliamentary_candidates.id', '=', 'candidate_election_results.candidate_id')
            ->join('political_parties', 'political_parties.id', '=', 'parliamentary_candidates.political_party_id')
            ->select(
                'regions.name as region_name',
                'regions.id as region_id',
                DB::raw("CONCAT(parliamentary_candidates.first_name, ' ', IFNULL(parliamentary_candidates.other_names, ''), ' ', parliamentary_candidates.last_name) AS candidate_name"),
                'political_parties.name as party_name',
                'candidate_election_results.candidate_id',
                DB::raw("CONCAT('" . asset('') . "', IF(parliamentary_candidates.image != '', CONCAT('uploads/', parliamentary_candidates.image), 'assets/images/user.png')) AS image_url"),
                DB::raw('SUM(candidate_election_results.votes) as total_votes'),
                DB::raw('100.0 * SUM(candidate_election_results.votes) / (SELECT SUM(votes) FROM candidate_election_results INNER JOIN election_results ON election_results.id = candidate_election_results.election_result_id WHERE regions.id = regions.id) AS percentage')
            )
            ->groupBy('regions.name', 'candidate_election_results.candidate_id');

        // Applying filters dynamically based on the provided array
        if (isset($filters['election_id'])) {
            $query->where('election_results.election_id', $filters['election_id']);
        }
        if (isset($filters['region_id'])) {
            $query->where('regions.id', $filters['region_id']);
        }
        if (isset($filters['constituency_id'])) {
            $query->where('constituencies.id', $filters['constituency_id']);
        }
        if (isset($filters['electoral_area_id'])) {
            $query->where('electoral_areas.id', $filters['electoral_area_id']);
        }
        if (isset($filters['polling_station_id'])) {
            $query->where('polling_stations.id', $filters['polling_station_id']);
        }

        // Execute the query and get results
        return $query->get()->groupBy('region_id');
    }


    public function getTotalVotesByConstituency(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        // Starting the query with necessary joins and eager loading relationships
        $query = CandidateElectionResult::with('parliamentaryCandidate.political_party')
            ->join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->join('polling_stations', 'polling_stations.id', '=', 'election_results.polling_station_id')
            ->join('electoral_areas', 'electoral_areas.id', '=', 'polling_stations.electoral_area_id')
            ->join('constituencies', 'constituencies.id', '=', 'electoral_areas.constituency_id')
            ->join('regions', 'regions.id', '=', 'constituencies.region_id')
            ->join('parliamentary_candidates', 'parliamentary_candidates.id', '=', 'candidate_election_results.candidate_id')
            ->join('political_parties', 'political_parties.id', '=', 'parliamentary_candidates.political_party_id')
            ->select(
                'constituencies.name as constituency_name',
                'constituencies.id as constituency_id',
                DB::raw("CONCAT(parliamentary_candidates.first_name, ' ', IFNULL(parliamentary_candidates.other_names, ''), ' ', parliamentary_candidates.last_name) AS candidate_name"),
                'political_parties.name as party_name',
                'candidate_election_results.candidate_id',
                DB::raw("CONCAT('" . asset('') . "', IF(parliamentary_candidates.image != '', CONCAT('uploads/', parliamentary_candidates.image), 'assets/images/user.png')) AS image_url"),
                DB::raw('SUM(candidate_election_results.votes) as total_votes'),
                DB::raw('100.0 * SUM(candidate_election_results.votes) / (SELECT SUM(votes) FROM candidate_election_results INNER JOIN election_results ON election_results.id = candidate_election_results.election_result_id INNER JOIN polling_stations ON polling_stations.id = election_results.polling_station_id INNER JOIN electoral_areas ON electoral_areas.id = polling_stations.electoral_area_id INNER JOIN constituencies ON constituencies.id = electoral_areas.constituency_id WHERE constituencies.id = constituencies.id) AS percentage')
            )
            ->groupBy('constituencies.name', 'candidate_election_results.candidate_id');

        // Applying filters dynamically based on the provided array
        if (isset($filters['election_id'])) {
            $query->where('election_results.election_id', $filters['election_id']);
        }
        if (isset($filters['region_id'])) {
            $query->where('regions.id', $filters['region_id']);
        }
        if (isset($filters['constituency_id'])) {
            $query->where('constituencies.id', $filters['constituency_id']);
        }
        if (isset($filters['electoral_area_id'])) {
            $query->where('electoral_areas.id', $filters['electoral_area_id']);
        }
        if (isset($filters['polling_station_id'])) {
            $query->where('polling_stations.id', $filters['polling_station_id']);
        }

        // Execute the query and get results
        $results = $query->get();

        // Optionally, group results by region id if needed for better organization in the output
        return $results->groupBy('constituency_id');
    }


    public function getTotalVotesByElectoralArea(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        // Starting the query with necessary joins and eager loading relationships
        $query = CandidateElectionResult::with('parliamentaryCandidate.political_party')
            ->join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->join('polling_stations', 'polling_stations.id', '=', 'election_results.polling_station_id')
            ->join('electoral_areas', 'electoral_areas.id', '=', 'polling_stations.electoral_area_id')
            ->join('constituencies', 'constituencies.id', '=', 'electoral_areas.constituency_id')
            ->join('regions', 'regions.id', '=', 'constituencies.region_id')
            ->join('parliamentary_candidates', 'parliamentary_candidates.id', '=', 'candidate_election_results.candidate_id')
            ->join('political_parties', 'political_parties.id', '=', 'parliamentary_candidates.political_party_id')
            ->select(
                'electoral_areas.name as electoral_area_name',
                'electoral_areas.id as electoral_area_id',
                DB::raw("CONCAT(parliamentary_candidates.first_name, ' ', IFNULL(parliamentary_candidates.other_names, ''), ' ', parliamentary_candidates.last_name) AS candidate_name"),
                'political_parties.name as party_name',
                'candidate_election_results.candidate_id',
                DB::raw("CONCAT('" . asset('') . "', IF(parliamentary_candidates.image != '', CONCAT('uploads/', parliamentary_candidates.image), 'assets/images/user.png')) AS image_url"),
                DB::raw('SUM(candidate_election_results.votes) as total_votes'),
                DB::raw('100.0 * SUM(candidate_election_results.votes) / (SELECT SUM(votes) FROM candidate_election_results INNER JOIN election_results ON election_results.id = candidate_election_results.election_result_id INNER JOIN polling_stations ON polling_stations.id = election_results.polling_station_id INNER JOIN electoral_areas ON electoral_areas.id = polling_stations.electoral_area_id WHERE electoral_areas.id = electoral_areas.id) AS percentage')
            )
            ->groupBy('electoral_areas.name', 'candidate_election_results.candidate_id');

        // Applying filters dynamically based on the provided array
        if (isset($filters['election_id'])) {
            $query->where('election_results.election_id', $filters['election_id']);
        }
        if (isset($filters['region_id'])) {
            $query->where('regions.id', $filters['region_id']);
        }
        if (isset($filters['constituency_id'])) {
            $query->where('constituencies.id', $filters['constituency_id']);
        }
        if (isset($filters['electoral_area_id'])) {
            $query->where('electoral_areas.id', $filters['electoral_area_id']);
        }
        if (isset($filters['polling_station_id'])) {
            $query->where('polling_stations.id', $filters['polling_station_id']);
        }

        // Execute the query and get results
        $results = $query->get();

        // Optionally, group results by constituency id for better organization in the output
        return $results->groupBy('electoral_area_id');
    }


    public function getTotalVotesByPollingStation(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        // Start building the query
        $query = CandidateElectionResult::with('parliamentaryCandidate.political_party')
            ->join('election_results', 'election_results.id', '=', 'candidate_election_results.election_result_id')
            ->join('polling_stations', 'polling_stations.id', '=', 'election_results.polling_station_id')
            ->join('electoral_areas', 'electoral_areas.id', '=', 'polling_stations.electoral_area_id')
            ->join('constituencies', 'constituencies.id', '=', 'electoral_areas.constituency_id')
            ->join('regions', 'regions.id', '=', 'constituencies.region_id')
            ->join('parliamentary_candidates', 'parliamentary_candidates.id', '=', 'candidate_election_results.candidate_id')
            ->join('political_parties', 'political_parties.id', '=', 'parliamentary_candidates.political_party_id')
            ->select(
                'polling_stations.name as polling_station_name',
                'polling_stations.id as polling_station_id',
                DB::raw("CONCAT(parliamentary_candidates.first_name, ' ', IFNULL(parliamentary_candidates.other_names, ''), ' ', parliamentary_candidates.last_name) AS candidate_name"),
                'political_parties.name as party_name',
                'candidate_election_results.candidate_id',
                DB::raw("IF(parliamentary_candidates.image != '', CONCAT('" . asset('uploads/') . "', parliamentary_candidates.image), '" . asset('assets/images/user.png') . "') AS image_url"),
                DB::raw('SUM(candidate_election_results.votes) as total_votes'),
                DB::raw('100.0 * SUM(candidate_election_results.votes) / (SELECT SUM(votes) FROM candidate_election_results WHERE election_result_id = election_results.id) AS percentage')
            )
            ->groupBy('polling_stations.id', 'candidate_election_results.candidate_id');

        // Applying dynamic filters based on the provided data
//        foreach ($filters as $key => $value) {
//            if (in_array($key, ['election_id', 'region_id', 'constituency_id', 'electoral_area_id']) && !empty($value)) {
//                $query->where('election_results.' . $key, $value);
//            }
//        }

        // Applying filters dynamically based on the provided array
        if (isset($filters['election_id'])) {
            $query->where('election_results.election_id', $filters['election_id']);
        }
        if (isset($filters['region_id'])) {
            $query->where('regions.id', $filters['region_id']);
        }
        if (isset($filters['constituency_id'])) {
            $query->where('constituencies.id', $filters['constituency_id']);
        }
        if (isset($filters['electoral_area_id'])) {
            $query->where('electoral_areas.id', $filters['electoral_area_id']);
        }
        if (isset($filters['polling_station_id'])) {
            $query->where('polling_stations.id', $filters['polling_station_id']);
        }

        // Execute the query and get results
        return $query->get()->groupBy('polling_station_id');
    }


    public function getPollingStationsWithNoVotes(int $electionId, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = PollingStation::leftJoin('election_results', function ($join) use ($electionId) {
            $join->on('polling_stations.id', '=', 'election_results.polling_station_id')
                ->where('election_results.election_id', '=', $electionId);
        })
            ->whereNull('election_results.id')  // This checks for polling stations with no associated election results
            ->select('polling_stations.*');  // Select all columns from polling stations

        // Apply filters based on additional parameters
        if (!empty($filters['region_id'])) {
            $query->whereHas('electoralArea.constituency', function ($query) use ($filters) {
                $query->where('region_id', $filters['region_id']);
            });
        }
        if (!empty($filters['constituency_id'])) {
            $query->whereHas('electoralArea', function ($query) use ($filters) {
                $query->where('constituency_id', $filters['constituency_id']);
            });
        }
        if (!empty($filters['electoral_area_id'])) {
            $query->where('electoral_area_id', $filters['electoral_area_id']);
        }

        // Execute the query and return the results
        return $query->get();
    }


    public function getPollingStationVoteSummary(int $electionId)
    {
        // Retrieve all constituencies with related polling station information
        return Constituency::with([
            'pollingStations' => function ($query) use ($electionId) {
                $query->leftJoin('election_results', function ($join) use ($electionId) {
                    $join->on('polling_stations.id', '=', 'election_results.polling_station_id')
                        ->where('election_results.election_id', '=', $electionId);
                });
            },
            'pollingStations.electionResults' => function ($query) use ($electionId) {
                // This assumes you only want to count the results for the specified election
                $query->where('election_id', '=', $electionId);
            }
        ])
            ->get()
            ->map(function ($constituency) {
                $totalVotes = $constituency->pollingStations->sum(function ($station) {
                    return $station->electionResults->sum('votes');
                });

                $pollingWithVotes = $constituency->pollingStations->filter(function ($station) {
                    return $station->electionResults->isNotEmpty();
                })->count();

                $pollingWithoutVotes = $constituency->pollingStations->filter(function ($station) {
                    return $station->electionResults->isEmpty();
                })->count();

                // Prepare the final output structure
                return [
                    'constituency_name' => $constituency->name,
                    'total_polling_stations' => $constituency->pollingStations->count(),
                    'polling_stations_with_votes' => $pollingWithVotes,
                    'polling_stations_without_votes' => $pollingWithoutVotes,
                    'total_votes' => $totalVotes  // Total votes across all polling stations in this constituency
                ];
            });
    }


    /**
     * Find the Election Result by id
     *
     * @param int $id
     *
     * @return ElectionResult
     */
    public function findElectionResultById(int $id): ElectionResult
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Election Result
     *
     * @param array $data
     * @param ElectionResult $electionResult
     * @return bool
     */
    public function updateElectionResult(array $data, ElectionResult $electionResult): bool
    {
        return $this->update($data, $electionResult->id);
    }

    /**
     * Delete Election Result
     *
     * @param ElectionResult $electionResult
     * @return bool
     * @throws \Exception
     */
    public function deleteElectionResult(ElectionResult $electionResult): bool
    {
        return $electionResult->delete();
    }
}
