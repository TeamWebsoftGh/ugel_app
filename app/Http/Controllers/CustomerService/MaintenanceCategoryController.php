<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Property\PropertyCategory;
use App\Services\Interfaces\IMaintenanceCategoryService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MaintenanceCategoryController extends Controller
{
    private IMaintenanceCategoryService $maintenanceCategoryService;

    /**
     * Create a new controller instance.
     *
     * @param IMaintenanceCategoryService $maintenanceCategoryService
     */
    public function __construct(IMaintenanceCategoryService $maintenanceCategoryService)
    {
        parent::__construct();
        $this->maintenanceCategoryService = $maintenanceCategoryService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = request()->all();
        if (request()->ajax())
        {
            $types = $this->maintenanceCategoryService->listMaintenanceCategories($data);
            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('parent_name', function ($row)
                {
                    return $row->parent->name??"Main";
                })
                ->addColumn('updated_at', function ($row)
                {
                    return Carbon::parse($row->updated_at)->format(env('Date_Format'));
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-maintenance-categories'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-maintenance-categories'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('customer-service.maintenance-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|Factory|\Illuminate\Foundation\Application|View
     */
    public function create()
    {
        $maintenance_category= new PropertyCategory();
        $categories = $this->maintenanceCategoryService->listMaintenanceCategories()->whereNull('parent_id')->get();
        if (request()->ajax()){
            return view('customer-service.maintenance-categories.edit', compact('maintenance_category', 'categories'));
        }

        return redirect()->route("maintenance-categories.index");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'short_name' => 'required',
            'is_active' => 'sometimes',
            'image' => 'nullable|image|max:10240|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $maintenance_category = $this->maintenanceCategoryService->findMaintenanceCategoryById($request->input("id"));
            $results = $this->maintenanceCategoryService->updateMaintenanceCategory($data, $maintenance_category);
        }else{
            $results = $this->maintenanceCategoryService->createMaintenanceCategory($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('maintenance-categories.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $maintenance_category = $this->maintenanceCategoryService->findMaintenanceCategoryById($id);
        if (request()->ajax()){
            return view('customer-service.maintenance-categories.edit', compact('maintenance_category'));
        }

        return redirect()->route("maintenance-categories.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $maintenance_category = $this->maintenanceCategoryService->findMaintenanceCategoryById($id);
        $categories = $this->maintenanceCategoryService->listMaintenanceCategories()->whereNull('parent_id')->get();
        if (request()->ajax()){
            return view('customer-service.maintenance-categories.edit', compact("maintenance_category", "categories"));
        }

        return redirect()->route("maintenance-categories.index");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $award = $this->maintenanceCategoryService->findMaintenanceCategoryById($id);
        $result = $this->maintenanceCategoryService->deleteMaintenanceCategory($award);

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
        $result = $this->maintenanceCategoryService->deleteMultipleMaintenanceCategories($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|\Illuminate\Foundation\Application
     */
    public function import()
    {
        return view('customer-service.maintenance-categories.import');
    }

    /**
     * Handle import of amenities.
     *
     * @return RedirectResponse
     */
    public function importPost(ImportRequest $request)
    {
        $result = $this->importExcel(new AmenitiesImport(), $request, "maintenance categories");

        if(isset($result->data) && $result->status == ResponseType::ERROR)
        {
            return view('shared.importError', ['failures' => $result->data]);
        }

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'maintenance-categories.index');
    }
}
