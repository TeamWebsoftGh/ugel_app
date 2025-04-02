<?php

namespace App\Http\Controllers\Legal;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Legal\CourtCase;
use App\Models\Legal\CourtHearing;
use App\Services\Legal\Interfaces\ICourtHearingService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourtHearingController extends Controller
{
    private ICourtHearingService $courtHearingService;

    public function __construct(ICourtHearingService $courtHearingService)
    {
        parent::__construct();
        $this->courtHearingService = $courtHearingService;
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
            $amenities = $this->courtHearingService->listCourtHearings($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('case_title', fn($row) => $row->courtCase->title)
                ->addColumn('case_number', fn($row) => $row->courtCase->case_number)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "court-hearing"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('legal.court-hearings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new CourtHearing();
        $court_cases = CourtCase::WhereNull('closed_at')->get();
        $item->is_active = 1;

        return view('legal.court-hearings.edit', compact('item', 'court_cases'));
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
            'court_case_id' => 'required',
            'notes' => 'required',
            'judge' => 'required',
            'date' => 'required',
            'time' => 'required',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->courtHearingService->updateCourtHearing($data, $this->courtHearingService->findCourtHearingById($request->input('id')))
            : $this->courtHearingService->createCourtHearing($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'court-hearings.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->courtHearingService->findCourtHearingById($id);
        $court_cases = CourtCase::WhereNull('closed_at')->get();
        return request()->ajax()
            ? view('legal.court-hearings.edit', compact('item', 'court_cases'))
            : redirect()->route('court-hearings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->courtHearingService->findCourtHearingById($id);
        $result = $this->courtHearingService->deleteCourtHearing($amenity);

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
        $result = $this->courtHearingService->deleteMultipleCourtHearings($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('legal.court-hearings.import');
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

        return $this->handleRedirect($result, 'court-hearings.index');
    }
}
