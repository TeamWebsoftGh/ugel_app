<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\EnquiryResource;
use App\Services\Helpers\DelegteHelper;
use App\Services\Interfaces\IParliamentaryCandidateService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ParliamentaryCandidateController extends MobileController
{
    /**
     * @var IParliamentaryCandidateService
     */
    private IParliamentaryCandidateService $parliamentaryCandidateService;

    public function __construct(IParliamentaryCandidateService $parliamentaryCandidateService)
    {
        parent::__construct();
        $this->parliamentaryCandidateService = $parliamentaryCandidateService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_type'] = 'parliamentary';
        $items = $this->parliamentaryCandidateService->listParliamentaryCandidates($data);
        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        $item = EnquiryResource::collection($paginatedItems);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item, $paginatedItems);

    }

    public function create()
    {
        $data['political_parties'] = DelegteHelper::getAllPoliticalParties();
        $data['constituency'] = DelegteHelper::getAllConstituencies();
        $data['elections'] = DelegteHelper::getAllElections("parliamentary");

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }

    public function show($id)
    {
        $item = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        $item = new EnquiryResource($item);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'political_party_id' => 'required',
            'election_id' => 'required',
            'constituency_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id', 'type');
        $item = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        $results = $this->parliamentaryCandidateService->updateParliamentaryCandidate($data, $item);

        if(isset($results->data))
        {
            $results->data = new EnquiryResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'political_party_id' => 'required',
            'election_id' => 'required',
            'constituency_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['type'] = 'parliamentary';
        $results = $this->parliamentaryCandidateService->createParliamentaryCandidate($data);

        if(isset($results->data))
        {
            $results->data = new EnquiryResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function destroy(int $id)
    {
        $candidate = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        $results = $this->parliamentaryCandidateService->deleteParliamentaryCandidate($candidate);

        return $this->apiResponseJson($results);
    }
}
