<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Requests\ElectionResultRequest;
use App\Http\Resources\ElectionResultResource;
use App\Services\Helpers\DelegteHelper;
use App\Services\Interfaces\IElectionResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ElectionResultController extends MobileController
{
    private IElectionResultService $electionResultService;

    public function __construct(IElectionResultService $electionResultService)
    {
        parent::__construct();
        $this->electionResultService = $electionResultService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $items = $this->electionResultService->listElectionResults($data);

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        $item = ElectionResultResource::collection($paginatedItems);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item, $paginatedItems);
    }

    public function show($id)
    {
        $item = $this->electionResultService->findElectionResultById($id);

        $item = new ElectionResultResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function details($id)
    {
        $item = $this->electionResultService->findElectionResultById($id);

        $item = new ElectionResultResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function create($type = "parliamentary")
    {
        if($type == "parliamentary")
        {
            $data['candidates'] = DelegteHelper::getAllParliamentaryCandidates();

        }else{
            $data['candidates'] = DelegteHelper::getAllParliamentaryCandidates("presidential");
        }

        $data['elections'] = DelegteHelper::getAllElections($type);
        $data['users'] = DelegteHelper::getAllUsers();
        $data['polling_stations'] = DelegteHelper::getAllPollingStations();

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }


    public function store(ElectionResultRequest $request)
    {
        $data = $request->except('_token', '_method', 'id');

        $results = $this->electionResultService->createElectionResult($data);

        if(isset($results->data))
        {
            $results->data = new ElectionResultResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function update(ElectionResultRequest $request, $id)
    {
        $data = $request->except('_token', '_method', 'id');

        $electionResult = $this->electionResultService->findElectionResultById($id);
        $results = $this->electionResultService->updateElectionResult($data, $electionResult);

        if(isset($results->data))
        {
            $results->data = new ElectionResultResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function totalVotes(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->totalVotes($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }

    public function totalVotesByRegion(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->totalVotesByRegion($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }


    public function totalVotesByConstituency(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->totalVotesByConstituency($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }

    public function totalVotesByElectoralArea(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
            'constituency_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->totalVotesByElectoralArea($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }


    public function totalVotesByPollingStation(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->totalVotesByPollingStation($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }

    public function pollingStationVoteSummary(Request $request)
    {
        $validatedData = $request->validate([
            'election_id' => 'required',
        ]);

        $data = $request->all();
        $items = $this->electionResultService->pollingStationVoteSummary($data);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items);
    }

    public function destroy(int $id)
    {
        $electionResult = $this->electionResultService->findElectionResultById($id);
        $results = $this->electionResultService->deleteElectionResult($electionResult);

        return $this->apiResponseJson($results);
    }
}
