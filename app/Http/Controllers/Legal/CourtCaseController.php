<?php

namespace App\Http\Controllers\Legal;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Legal\CourtCase;
use App\Services\Legal\Interfaces\ICourtCaseService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourtCaseController extends Controller
{
    private ICourtCaseService $courtCaseService;

    public function __construct(ICourtCaseService $courtCaseService)
    {
        parent::__construct();
        $this->courtCaseService = $courtCaseService;
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
            $amenities = $this->courtCaseService->listCourtCases($request->all(), 'updated_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "booking-periods"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('legal.court-cases.index');
    }

    public function active(Request $request)
    {
        if ($request->ajax()) {
            $amenities = $this->courtCaseService->listCourtCases($request->all())->whereNull('closed_at');
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "booking-periods"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('legal.court-cases.active');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new CourtCase();
        $item->is_active = 1;

        return view('legal.court-cases.edit', compact('item'));
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
            'case_number' => 'required',
            'title' => 'required',
            'note' => 'required',
            'filed_at' => 'required',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->courtCaseService->updateCourtCase($data, $this->courtCaseService->findCourtCaseById($request->input('id')))
            : $this->courtCaseService->createCourtCase($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'court-cases.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->courtCaseService->findCourtCaseById($id);
        return request()->ajax()
            ? view('legal.court-cases.edit', compact('item'))
            : redirect()->route('court-cases.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->courtCaseService->findCourtCaseById($id);
        $result = $this->courtCaseService->deleteCourtCase($amenity);

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
        $result = $this->courtCaseService->deleteMultipleCourtCases($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('legal.court-cases.import');
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

        return $this->handleRedirect($result, 'court-cases.index');
    }
}
