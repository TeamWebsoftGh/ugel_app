<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyType;
use App\Services\Interfaces\IPropertyCategoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyCategoryController extends Controller
{
    private IPropertyCategoryService $propertyCategoryService;

    /**
     * Create a new controller instance.
     *
     * @param IPropertyCategoryService $propertyCategoryService
     */
    public function __construct(IPropertyCategoryService $propertyCategoryService)
    {
        parent::__construct();
        $this->propertyCategoryService = $propertyCategoryService;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $data = request()->all();
		$types = $this->propertyCategoryService->listPropertyCategories($data, "updated_at", "desc");
        if (request()->ajax())
        {
            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('category', function ($row)
                {
                    return $row->property_category->name ?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-property-categories'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-property-categories'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.property-categories.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View
     */
    public function create()
    {
        // $property_type = new PropertyType();
        $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-types.edit', compact('property_type', 'categories'));
        }

        return redirect()->route("property-types.index");
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
            $property_Category = $this->propertyCategoryService->findPropertyCategoryById($request->input("id"));
            $results = $this->propertyCategoryService->updatePropertyCategory($data, $property_Category);
        }else{
            $results = $this->propertyCategoryService->createPropertyCategory($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property-categories.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function show($id)
	{
        $property_Category = $this->propertyCategoryService->findPropertyCategoryById($id);
        // $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-categories.edit', compact('property_category', 'categories'));
        }

        return redirect()->route("property-categories.index");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function edit($id)
	{
        $property_Category = $this->propertyCategoryService->findPropertyCategoryById($id);
        // $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-categories.edit');
        }

        return redirect()->route("property-categories.index");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(int $id)
	{
        $award = $this->propertyCategoryService->findPropertyCategoryById($id);
        $result = $this->propertyCategoryService->deletePropertyCategory($award);

        return $this->responseJson($result);
	}
}
