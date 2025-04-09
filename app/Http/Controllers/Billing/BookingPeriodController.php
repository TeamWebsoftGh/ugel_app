<?php

namespace App\Http\Controllers\Billing;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Billing\BookingPeriod;
use App\Models\Property\Amenity;
use App\Services\Billing\Interfaces\IBookingPeriodService;
use App\Services\Helpers\PropertyHelper;
use App\Services\Properties\Interfaces\IAmenityService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingPeriodController extends Controller
{
    private IBookingPeriodService $bookingPeriodService;

    public function __construct(IBookingPeriodService $bookingPeriodService)
    {
        parent::__construct();
        $this->bookingPeriodService = $bookingPeriodService;
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
            $items = $this->bookingPeriodService->listBookingPeriods($request->all(), 'updated_at');
            return datatables()->of($items)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "booking-periods"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('billing.booking-periods.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new BookingPeriod();
        $hostels = PropertyHelper::getAllHostels();

        $item->is_active = 1;

        return view('billing.booking-periods.edit', compact('item', 'hostels'));
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
            'name' => 'required',
            'short_name' => 'nullable',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->bookingPeriodService->updateBookingPeriod($data, $this->bookingPeriodService->findBookingPeriodById($request->input('id')))
            : $this->bookingPeriodService->createBookingPeriod($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'booking-periods.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->bookingPeriodService->findBookingPeriodById($id);
        $hostels = PropertyHelper::getAllHostels();
        // Fetch existing prices and rent types for the given Booking Period
        $propertyUnitPrices = $item->propertyUnitPrices;

        // Attach existing prices and rent types to hostels
        foreach ($hostels as $hostel) {
            $priceData = $propertyUnitPrices->where('property_unit_id', $hostel->id)->first();
            $hostel->existing_price = $priceData->price ?? null;
            $hostel->existing_rent_type = $priceData->rent_type ?? null;
        }

        return request()->ajax()
            ? view('billing.booking-periods.edit', compact('item', 'hostels'))
            : redirect()->route('booking-periods.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->bookingPeriodService->findBookingPeriodById($id);
        $result = $this->bookingPeriodService->deleteBookingPeriod($amenity);

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
        $result = $this->bookingPeriodService->deleteMultipleBookingPeriods($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('billing.booking-periods.import');
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

        return $this->handleRedirect($result, 'booking-periods.index');
    }
}
