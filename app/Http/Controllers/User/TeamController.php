<?php

namespace App\Http\Controllers\User;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Auth\Team;
use App\Services\Auth\Interfaces\ITeamService;
use App\Services\Helpers\PropertyHelper;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private ITeamService $teamService;

    public function __construct(ITeamService $teamService)
    {
        parent::__construct();
        $this->teamService = $teamService;
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
            $amenities = $this->teamService->listTeams($request->all());
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "teams"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user-access.teams.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory|Application
     */
    public function create()
    {
        $item = new Team();
        $hostels = PropertyHelper::getAllHostels();

        $item->is_active = 1;

        return view('user-access.teams.edit', compact('item', 'hostels'));
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
            'user_type' => 'required',
            'is_active' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $result = $request->filled('id')
            ? $this->teamService->updateTeam($data, $this->teamService->findTeamById($request->input('id')))
            : $this->teamService->createTeam($data);

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'admin.teams.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View|Factory|Application|RedirectResponse
     */
    public function edit(int $id)
    {
        $item = $this->teamService->findTeamById($id);

        return request()->ajax()
            ? view('user-access.teams.edit', compact('item'))
            : redirect()->route('admin.teams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $amenity = $this->teamService->findTeamById($id);
        $result = $this->teamService->deleteTeam($amenity);

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
        $result = $this->teamService->deleteMultipleTeams($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|Application
     */
    public function import()
    {
        return view('user-access.teams.import');
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
