<?php

namespace App\Http\Controllers\Organization;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Organization\Department;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\IDepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IDepartmentService
     */
    private IDepartmentService $departmentService;

    /**
     * CategoryController constructor.
     *
     * @param IDepartmentService $departmentService
     */
    public function __construct(IDepartmentService $departmentService)
    {
        parent::__construct();
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        $lists = $this->departmentService->listdepartments('updated_at', 'asc');
        $department = new Department();
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
        return view('organization.departments.create', compact('department'));
    }

    public function show(Request $request, $id)
    {
        $lists = $this->departmentService->listdepartments();
        $department = $this->departmentService->findDepartmentById($id);

        if ($request->ajax()){
            return view('organization.departments.edit', compact('department'));
        }

        return view('organization.departments.index', compact('lists'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $Department = $this->departmentService->findDepartmentById($request->input("id"));
            $results = $this->departmentService->updateDepartment($data, $Department);
        }else{
            $results = $this->departmentService->createDepartment($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('departments.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->departmentService->findDepartmentById($id);
        $result = $this->departmentService->deleteDepartment($writer);

        return $this->responseJson($result);
    }
}
