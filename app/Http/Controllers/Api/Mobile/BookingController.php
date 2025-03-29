<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\EnquiryResource;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Interfaces\IParliamentaryCandidateService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookingController extends MobileController
{
    /**
     * @var IParliamentaryCandidateService
     */
    private IBookingService $bookingService;

    public function __construct(IBookingService $bookingService)
    {
        parent::__construct();
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_type'] = 'presidential';
        $items = $this->bookingService->listBookings($data);
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
        $data['hostels'] = PropertyHelper::getAllHostels();
        $data['booking_periods'] = PropertyHelper::getAllBookingPeriods();

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }

    public function show($id)
    {
        $item = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        if ($item->type != 'presidential')
        {
            abort(404);
        }
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
        ]);

        $data = $request->except('_token', '_method', 'id', 'type');
        $item = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        if ($item->type != 'presidential')
        {
            abort(404);
        }
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
            'booking_period_id' => 'required',
            'property_id' => 'required',
            'property_unit_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $results = $this->bookingService->createBooking($data);

        if(isset($results->data))
        {
            $results->data = new EnquiryResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function destroy(int $id)
    {
        $candidate = $this->parliamentaryCandidateService->findParliamentaryCandidateById($id);
        if ($candidate->type != 'presidential')
        {
            abort(404);
        }
        $results = $this->parliamentaryCandidateService->deleteParliamentaryCandidate($candidate);

        return $this->apiResponseJson($results);
    }
}
