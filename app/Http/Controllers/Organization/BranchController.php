<?php

namespace App\Http\Controllers\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Organization\Branch;
use App\Models\Settings\Region;
use App\Services\Legal\Interfaces\ICourtCaseService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function datatables;
use function redirect;
use function request;
use function view;

class BranchController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var ICourtCaseService
     */
    private ICourtCaseService $branchService;

    /**
     * CategoryController constructor.
     *
     * @param ICourtCaseService $branchService
     */
    public function __construct(ICourtCaseService $branchService)
    {
        parent::__construct();
        $this->branchService = $branchService;
    }

    public function index()
    {
        $branches = $this->branchService->listBranches('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($branches)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->make(true);
        }
        $branch = new Branch();
        $regions = Region::where('is_active', 1)->get();
        return view('organization.branches.create', compact('branch', 'branches', 'regions'));
    }

    public function show(Request $request, $id)
    {
        $branches = $this->branchService->listBranches();
        $branch = $this->branchService->findBranchById($id);
        $regions = Region::where('is_active', 1)->get();

        if ($request->ajax()){
            return view('organization.branches.edit', compact('branch', 'regions'));
        }

        return view('organization.branches.index', compact('branches', 'regions'));
    }

    public function create()
    {
        $branches = $this->branchService->listBranches('updated_at');
        $branch = new Branch();
        $regions = Region::where('is_active', 1)->get();
        return view('organization.branches.create', compact('branch', 'branches', 'regions'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'branch_name' => 'required|unique:branches,branch_name,'.$request->input("id"),
            'region_id' => 'required',
            'email_address' => 'email|nullable',
            'phone_number' => 'nullable|phone',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $branch = $this->branchService->findBranchById($request->input("id"));
            $results = $this->branchService->updateBranch($data, $branch);
        }else{
            $results = $this->branchService->createBranch($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('organization.branches.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->branchService->findBranchById($id);
        $result = $this->branchService->deleteBranch($writer);

        return $this->responseJson($result);
    }
}
