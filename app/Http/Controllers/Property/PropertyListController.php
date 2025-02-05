<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyType;
use App\Services\Interfaces\Properties\IPropertyTypeService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PropertyListController extends Controller
{
    private IPropertyTypeService $propertyTypeService;

    /**
     * Create a new controller instance.
     *
     * @param IPropertyTypeService $propertyTypeService
     */
    public function __construct(IPropertyTypeService $propertyTypeService)
    {
        parent::__construct();
        $this->propertyTypeService = $propertyTypeService;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $data = request()->all();
		$types = $this->propertyTypeService->listPropertyTypes($data, "updated_at", "desc");
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
                    if (user()->can('update-property-types'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-property-types'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.property-types.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View
     */
    public function create()
    {
        $property_type = new PropertyType();
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
            $property_type = $this->propertyTypeService->findPropertyTypeById($request->input("id"));
            $results = $this->propertyTypeService->updatePropertyType($data, $property_type);
        }else{
            $results = $this->propertyTypeService->createPropertyType($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property-types.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function show($id)
	{
        $property_type = $this->propertyTypeService->findPropertyTypeById($id);
        $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-types.edit', compact('property_type', 'categories'));
        }

        return redirect()->route("property-types.index");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function edit($id)
	{
        $property_type = $this->propertyTypeService->findPropertyTypeById($id);
        $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-types.edit', compact('property_type', 'categories'));
        }

        return redirect()->route("property-types.index");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(int $id)
	{
        $award = $this->propertyTypeService->findPropertyTypeById($id);
        $result = $this->propertyTypeService->deletePropertyType($award);

        return $this->responseJson($result);
	}
}
