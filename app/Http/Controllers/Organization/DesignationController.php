<?php

namespace App\Http\Controllers\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Organization\Designation;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\IDesignationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DesignationController extends Controller
{
    use JsonResponseTrait;
    /**
     *
     */
    private IDesignationService $designationService;

    /**
     * CategoryController constructor.
     *
     * @param IDesignationService $designationService
     */
    public function __construct(IDesignationService $designationService)
    {
        parent::__construct();
        $this->designationService = $designationService;
    }

    public function index()
    {
        $lists = $this->designationService->listDesignations('updated_at');
        $designation = new Designation();
        if (request()->ajax())
        {
            return datatables()->of($lists)
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
        return view('organization.designations.create', compact('designation'));
    }

    public function show(Request $request, $id)
    {
        $designations = $this->designationService->listDesignations();
        $designation = $this->designationService->findDesignationById($id);

        if ($request->ajax()){
            return view('organization.designations.edit', compact('designation'));
        }

        return view('organization.designations.index', compact('designations'));
    }

    public function create()
    {
        $lists = $this->designationService->listDesignations('updated_at');
        $designation = new Designation();

        return view('organization.designations.create', compact('designation', 'lists'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'designation_name' => 'required',
            'department_id' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $designation = $this->designationService->findDesignationById($request->input("id"));
            $results = $this->designationService->updateDesignation($data, $designation);
        }else{
            $results = $this->designationService->createDesignation($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('organization.designations.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->designationService->findDesignationById($id);
        $result = $this->designationService->deleteDesignation($writer);

        return $this->responseJson($result);
    }
}
