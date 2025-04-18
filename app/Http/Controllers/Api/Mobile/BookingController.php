<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\BookingResource;
use App\Http\Resources\InvoiceResource;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use Illuminate\Http\Request;

class BookingController extends MobileController
{
    /**
     * @var IBookingService
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
        $data['filter_client'] =  user()->client_id;
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $query = $this->bookingService->listBookings($data);

        if ($perPage > 0) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            $resource = BookingResource::collection($paginator); // Resource handles paginator

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource, $paginator);
        } else {
            $items = $query->get();
            $resource = BookingResource::collection($items);

            return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $resource);
        }
    }

    public function lookup()
    {
        $data['booking_periods'] = PropertyHelper::getActiveBookingPeriods();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }

    public function show($id)
    {
        $item = $this->bookingService->findBookingById($id);
        if($item->client_id !=  user()->client_id){
            abort(404);
        }

        $item = new BookingResource($item);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'booking_period_id' => 'required|exists:booking_periods,id',
          //  'property_id' => 'required|exists:properties,id',
            'property_unit_id' => 'required|exists:property_units,id',
            'room_id' => 'nullable|exists:rooms,id',
            // 'client_id' => 'required|exists:clients,id',
            'lease_start_date' => 'sometimes|date',
            'lease_end_date' => 'sometimes|date|after_or_equal:lease_start_date',
        ]);

        $data = $request->except('_token', '_method', 'id', 'client_id');
        $item = $this->bookingService->findBookingById($data['booking_id']);

        $results = $this->bookingService->updateBooking($data, $item);

        if(isset($results->data))
        {
            $results->data = new BookingResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'booking_period_id' => 'sometimes|exists:booking_periods,id',
            'property_id' => 'nullable|exists:properties,id',
            'property_unit_id' => 'required_without:room_id|exists:property_units,id',
            'room_id' => 'nullable|exists:rooms,id',
            'lease_start_date' => 'sometimes|date',
            'lease_end_date' => 'sometimes|date|after_or_equal:lease_start_date',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['client_id'] = user()->client_id;
        $results = $this->bookingService->createBooking($data);

        if(isset($results->data))
        {
            $results->data = new InvoiceResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function destroy(int $id)
    {
        $booking = $this->bookingService->findBookingById($id);
        $results = $this->bookingService->deleteBooking($booking);

        return $this->apiResponseJson($results);
    }
}
