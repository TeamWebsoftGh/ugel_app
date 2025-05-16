<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Helpers\DataTableActionHelper;
use App\Models\Property\PropertyUnit;
use App\Services\Helpers\PropertyHelper;
use App\Services\Properties\Interfaces\IPropertyService;
use App\Services\Properties\Interfaces\IPropertyUnitService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PropertyUnitController extends Controller
{
    private IPropertyUnitService $propertyUnitService;
    private IPropertyService $propertyService;

    /**
     * Create a new controller instance.
     *
     * @param IPropertyUnitService $propertyUnitService
     */
    public function __construct(IPropertyUnitService $propertyUnitService, IPropertyService $propertyService)
    {
        parent::__construct();
        $this->propertyUnitService = $propertyUnitService;
        $this->propertyService = $propertyService;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return Factory|\Illuminate\Foundation\Application|object|View
     */
	public function index(Request $request)
	{
        $data = request()->all();
		$types = $this->propertyUnitService->listPropertyUnits($data);
        if (request()->ajax())
        {
            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('property_name', function ($row)
                {
                    return $row->property->property_name ?? '';
                })
                ->addColumn('property_type_name', function ($row)
                {
                    return $row->property->propertyType->name ?? '';
                })
                ->addColumn('formatted_amount', function ($row)
                {
                    return format_money($row->rent_amount) ?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', function ($row) {
                    return DataTableActionHelper::generate($row->id, [
                        //'view' => true,
                        'view' => [
                            'url' => route('property-units.show', $row->id),
                        ],
                        'edit' => user()->can("update-property-units"),
                        'delete' => user()->can("delete-property-units"),
                    ],[
                        [
                            'label' => 'View Rooms',
                            'icon' => 'ri-home-4-line',
                            'url' => route("rooms.index", ['filter_property_unit' => $row->id])
                        ]]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.property-units.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $property_unit = new PropertyUnit();
        $properties = $this->propertyService->listProperties();
        $amenities = PropertyHelper::getAllAmenities();
        if (request()->ajax()){
            return view('property.property-units.edit', compact('property_unit', 'properties','amenities'));
        }

        return redirect()->route("property-units.index");
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
            'unit_name' => 'required',
            'property_id' => 'required',
            'is_active' => 'sometimes',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $property_unit = $this->propertyUnitService->findPropertyUnitById($request->input("id"));
            $results = $this->propertyUnitService->updatePropertyUnit($data, $property_unit);
        }else{
            $results = $this->propertyUnitService->createPropertyUnit($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property-units.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function show($id)
	{
        $property_unit = $this->propertyUnitService->findPropertyUnitById($id);
        // $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.property-units.edit', compact('property_unit'));
        }

        return view('property.property-units.show', compact('property_unit'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function edit($id)
	{
        $property_unit = $this->propertyUnitService->findPropertyUnitById($id);
        $properties = $this->propertyService->listProperties();
        $amenities = PropertyHelper::getAllAmenities();

        if (request()->ajax()){
            return view('property.property-units.edit', compact('property_unit', 'properties','amenities'));
        }

        return redirect()->route("property-units.index");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(int $id)
	{
        $award = $this->propertyUnitService->findPropertyUnitById($id);
        $result = $this->propertyUnitService->deletePropertyUnit($award);

        return $this->responseJson($result);
	}
}
