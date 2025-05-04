<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Helpers\DataTableActionHelper;
use App\Models\Property\Property;
use App\Services\Helpers\PropertyHelper;
use App\Services\Properties\Interfaces\IPropertyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyController extends Controller
{
    /**
     * @var IPropertyService
     */
    private IPropertyService $propertyService;

    /**
     * PropertyController constructor.
     *
     * @param IPropertyService $propertyService
     */
    public function __construct(IPropertyService $propertyService)
    {
        parent::__construct();
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $properties = $this->propertyService->listProperties($request->all(), "updated_at", "desc");

            return datatables()->of($properties)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('category', fn($row) => $row->propertyType->propertyCategory->name ?? '')
                ->addColumn('type', fn($row) => $row->propertyType->name ?? '')
                ->addColumn('purpose', fn($row) => $row->propertyPurpose->name ?? '')
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', function ($row) {
                    return DataTableActionHelper::generate($row->id, [
                        //'view' => true,
                        'view' => [
                            'url' => route('properties.show', $row->id),
                        ],
                        'edit' => user()->can("update-properties"),
                        'delete' => user()->can("delete-properties"),
                    ],[
                        [
                            'label' => 'View Units',
                            'icon' => 'ri-home-4-line',
                            'url' => route("property-units.index", ['filter_property' => $row->id])
                        ],
                        [
                            'label' => 'View Rooms',
                            'icon' => 'ri-home-4-line',
                            'url' => route("rooms.index", ['filter_property' => $row->id])
                        ]]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.properties.index');
    }


    public function propertyLease()
    {
        return view('property.properties.lease');
    }


    public function all(Request $request)
    {
        $data = request()->all();
        $properties = $this->propertyService->listProperties($data, "updated_at", "desc")->paginate();
        return view('property.properties.all', compact('properties'));
    }



    public function create()
    {
        $property = new Property();
        $property->is_active = 1;
        $property_purposes = PropertyHelper::getAllPropertyPurposes();

        if (request()->ajax()){
            return view('property.properties.edit', compact('property', "property_purposes"));
        }

        return view("property.properties.create", compact("property", "property_purposes"));
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
            'property_name' => 'required',
            'property_purpose_id' => 'required',
            'property_category_id' => 'required',
            'property_type_id' => 'required',
            'is_active' => 'required',
            'description' => 'nullable|string',
            'country_id' => 'required|integer',
            'region_id' => 'required|integer',
            'city_id' => 'required|integer',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048']
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $meeting = $this->propertyService->findPropertyById($request->input("id"));
            $results = $this->propertyService->updateProperty($data, $meeting);
        }else{
            $results = $this->propertyService->createProperty($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('service-types.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Request $request, $id)
    {
        $property = $this->propertyService->findPropertyById($id);

        if ($request->ajax()){
            return view('property.properties.edit', compact('property'));
        }

        return view('property.properties.show', compact('property'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request, $id)
    {
        $property = $this->propertyService->findPropertyById($id);
        $property_purposes = PropertyHelper::getAllPropertyPurposes();

        if ($request->ajax()){
            return view('property.properties.edit', compact('property', "property_purposes"));
        }

        return view("property.properties.create", compact("property", "property_purposes"));
    }


    /**
     *
     * /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $meeting = $this->propertyService->findPropertyById($id);
        $result = $this->propertyService->deleteProperty($meeting);

        return $this->responseJson($result);
    }
}
