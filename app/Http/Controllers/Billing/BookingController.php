<?php

namespace App\Http\Controllers\Billing;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Billing\Booking;
use App\Models\Property\Amenity;
use App\Services\Billing\Interfaces\IBookingService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Properties\Interfaces\IAmenityService;
use App\Services\Properties\Interfaces\IPropertyService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private IBookingService $bookingService;
    private IPropertyService $propertyService;

    public function __construct(IBookingService $bookingService, IPropertyService $propertyService)
    {
        parent::__construct();
        $this->bookingService = $bookingService;
        $this->propertyService = $propertyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|Factory|Application|JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $amenities = $this->bookingService->listBookings($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('client_name', fn($row) => $row->client->fullname)
                ->addColumn('client_number', fn($row) => $row->client->client_number)
                ->addColumn('booking_type', fn($row) => $row->bookingPeriod->name)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('updated_at', fn($row) => Carbon::parse($row->updated_at)->format(env('Date_Format')))
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "amenities"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('billing.bookings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new Booking();
        $properties = $this->propertyService->listLeaseProperties(['filter_active' => 1]);
        $booking_periods =PropertyHelper::getActiveBookingPeriods();

        $item->is_active = 1;

        return request()->ajax()
            ? view('billing.bookings.edit', compact('item', 'properties', 'booking_periods'))
            : redirect()->route('bookings.index');

    }

    /**
     * Store or update the resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'booking_period_id' => 'required',
            'property_id' => 'required',
            'property_unit_id' => 'required',
            'client_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->bookingService->updateBooking($data, $this->bookingService->findBookingById($request->input('id')))
            : $this->bookingService->createBooking($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'bookings.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->bookingService->findBookingById($id);
        $properties = $this->propertyService->listLeaseProperties(['filter_active' => 1]);
        $booking_periods =PropertyHelper::getActiveBookingPeriods();

        $item->is_active = 1;

        return request()->ajax()
            ? view('billing.bookings.edit', compact('item', 'properties', 'booking_periods'))
            : redirect()->route('bookings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->bookingService->findBookingById($id);
        $result = $this->bookingService->deleteBooking($amenity);

        return $this->responseJson($result);
    }

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->bookingService->deleteMultipleBookings($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('billing.bookings.import');
    }

    /**
     * Handle import of amenities.
     *
     * @return RedirectResponse
     */
    public function importPost(ImportRequest $request)
    {
        $result = $this->importExcel(new AmenitiesImport(), $request, "amenities");

        if(isset($result->data) && $result->status == ResponseType::ERROR)
        {
            return view('shared.importError', ['failures' => $result->data]);
        }

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'bookings.index');
    }
}
