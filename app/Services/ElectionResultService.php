<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Election\CandidateElectionResult;
use App\Models\Election\ElectionResult;
use App\Repositories\Interfaces\IPollingStationRepository;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IElectionResultRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IElectionResultService;
use Illuminate\Support\Collection;

class ElectionResultService extends ServiceBase implements IElectionResultService
{
    use UploadableTrait;

    private IElectionResultRepository $electionResultRepo;
    private IPollingStationRepository $pollingStationRepo;

    public function __construct(IElectionResultRepository $electionResultRepository, IPollingStationRepository $pollingStationRepository)
    {
        parent::__construct();
        $this->electionResultRepo = $electionResultRepository;
        $this->pollingStationRepo = $pollingStationRepository;
    }

    public function listElectionResults(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->electionResultRepo->listElectionResults($filter, $order, $sort);
    }

    public function totalVotes(array $filter = null): Collection
    {
        return $this->electionResultRepo->getTotalVotes($filter);
    }

    public function totalVotesByRegion(array $filter = null): Collection
    {
        return $this->electionResultRepo->getTotalVotesByRegion($filter);
    }

    public function totalVotesByConstituency(array $filter = null)
    {
        return $this->electionResultRepo->getTotalVotesByConstituency($filter);
    }

    public function totalVotesByElectoralArea(array $filter = null)
    {
        return $this->electionResultRepo->getTotalVotesByElectoralArea($filter);
    }

    public function totalVotesByPollingStation(array $filter = null)
    {
        return $this->electionResultRepo->getTotalVotesByPollingStation($filter);
    }

    public function pollingStationsWithNoVotes(array $filters = [])
    {
        return $this->electionResultRepo->getPollingStationsWithNoVotes($filters['election_id'], $filters);
    }


    public function pollingStationVoteSummary(array $filters = [])
    {
        return $this->electionResultRepo->getPollingStationVoteSummary($filters['election_id'], $filters);
    }

    public function createElectionResult(array $params)
    {
        $electionResult = null;

        try {
            $ps = $this->pollingStationRepo->find($params["polling_station_id"]);
            $params['polling_station_name'] = $ps->name;
            $params['polling_station_code'] = $ps->code;

            if(user()->hasRole('agent') && $params["polling_station_id"] != user()->polling_station_id)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You are not allowed to add result for ".$ps->name;

                return $this->response;
            }

            $exist = ElectionResult::where(['polling_station_id' => $params['polling_station_id'], 'election_id' => $params['election_id']])->exists();

            if($exist)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Election result already added for this polling station - ".$ps->name;

                return $this->response;
            }

            $electionResult = $this->electionResultRepo->createElectionResult($params);
            if (isset($params['candidate_votes']))
            {
                foreach ($params['candidate_votes'] as $candidateId => $votesData) {
                    // Check if total_valid_votes is zero to avoid division by zero
                    if ($electionResult->total_valid_votes > 0) {
                        $percentage = ($votesData['votes'] / $electionResult->total_valid_votes) * 100;
                        // Format the percentage to two decimal places
                        $percentage = number_format($percentage, 2, '.', '');
                    } else {
                        $percentage = null; // Set to null if total_valid_votes is zero
                    }

                    if(isset($votesData['candidate_id']))
                    {
                        $candidateId = $votesData['candidate_id'];
                    }

                    $candidateResult = CandidateElectionResult::updateOrCreate(
                        [
                            'election_result_id' => $electionResult->id,
                            'candidate_id' => $candidateId
                        ],
                        [
                            'votes' => $votesData['votes'],
                            'percentage' => $percentage
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), new ElectionResult(), 'create-election-result-failed');
        }

        if (!$electionResult)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        log_activity(ResponseMessage::DEFAULT_SUCCESS_CREATE, $electionResult, 'create-election-result-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
        $this->response->data = $electionResult;

        return $this->response;
    }

    public function findElectionResultById(int $id): ElectionResult
    {
        return $this->electionResultRepo->findElectionResultById($id);
    }

    public function updateElectionResult(array $params, ElectionResult $electionResult)
    {
        $result = false;

        try {
            $ps = $this->pollingStationRepo->find($params["polling_station_id"]);
            $params['polling_station_name'] = $ps?->name;
            $params['polling_station_code'] = $ps?->code;

            if(user()->hasRole('agent') && $params["polling_station_id"] != user()->polling_station_id)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You are not allowed to add result for ".$ps->name;

                return $this->response;
            }
            $result = $this->electionResultRepo->updateElectionResult($params, $electionResult);
            if (isset($params['candidate_votes']))
            {
                foreach ($params['candidate_votes'] as $candidateId => $votesData) {
                    // Check if total_valid_votes is zero to avoid division by zero
                    if ($electionResult->total_valid_votes > 0) {
                        $percentage = ($votesData['votes'] / $electionResult->total_valid_votes) * 100;
                        // Format the percentage to two decimal places
                        $percentage = number_format($percentage, 2, '.', '');
                    } else {
                        $percentage = null; // Set to null if total_valid_votes is zero
                    }

                    if(isset($votesData['candidate_id']))
                    {
                        $candidateId = $votesData['candidate_id'];
                    }

                    $candidateResult = CandidateElectionResult::updateOrCreate(
                        [
                            'election_result_id' => $electionResult->id,
                            'candidate_id' => $candidateId
                        ],
                        [
                            'votes' => $votesData['votes'],
                            'percentage' => $percentage
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $electionResult, 'update-election-result-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        log_activity('You have successfully updated an Election Result ' . $electionResult->id, $electionResult, 'update-election-result-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully updated an Election Result';
        $this->response->data = $electionResult;

        return $this->response;
    }

    public function deleteElectionResult(ElectionResult $electionResult)
    {
        $result = false;
        try {
            $result = $this->electionResultRepo->deleteElectionResult($electionResult);
        } catch (\Exception $ex) {
            log_error(format_exception($ex), $electionResult, 'delete-election-result-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        log_activity('You have successfully deleted Election Result ' . $electionResult->id, $electionResult, 'delete-election-result-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully deleted Election Result';

        return $this->response;
    }
}
