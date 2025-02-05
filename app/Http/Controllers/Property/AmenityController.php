<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Property\Amenity;
use App\Services\Interfaces\Properties\IAmenityService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    private IAmenityService $amenityService;

    public function __construct(IAmenityService $amenityService)
    {
        parent::__construct();
        $this->amenityService = $amenityService;
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
            $amenities = $this->amenityService->listAmenities($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('updated_at', fn($row) => Carbon::parse($row->updated_at)->format(env('Date_Format')))
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "amenities"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.amenities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new Amenity();
        $item->is_active = 1;

        return view('property.amenities.edit', compact('item'));
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
            ? $this->amenityService->updateAmenity($data, $this->amenityService->findAmenityById($request->input('id')))
            : $this->amenityService->createAmenity($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'amenities.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->amenityService->findAmenityById($id);

        return request()->ajax()
            ? view('property.amenities.edit', compact('item'))
            : redirect()->route('amenities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->amenityService->findAmenityById($id);
        $result = $this->amenityService->deleteAmenity($amenity);

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
        $result = $this->amenityService->deleteMultipleAmenities($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('property.amenities.import');
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

        return $this->handleRedirect($result, 'amenities.index');
    }
}
