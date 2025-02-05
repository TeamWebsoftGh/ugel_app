<?php

namespace App\Http\Controllers\Property;

use App\Constants\ResponseType;
use App\Models\Property\Property;
use App\Models\Property\PropertyPurpose;
use App\Services\Helpers\PropertyHelper;
use App\Services\Interfaces\IPropertyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyController extends \App\Abstracts\Http\Controller
{
    /**
     * @var IPropertyService
     */
    private IPropertyService $propertyService;

    /**
     * PropertyController constructor.
     *
     * @param IPropertyService $serviceTypeService
     */
    public function __construct(IPropertyService $serviceTypeService)
    {
        parent::__construct();
        $this->propertyService = $serviceTypeService;
    }

    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = request()->all();
            $properties = $this->propertyService->listProperties($data, "updated_at", "desc");

            return datatables()->of($properties)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('category', function ($row)
                {
                    return $row->propertyType->propertyCategory->name ?? '';
                })
                ->addColumn('type', function ($row)
                {
                    return $row->propertyType->name ?? '';
                })
                ->addColumn('purpose', function ($row)
                {
                    return $row->propertyPurpose->name ?? '';
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

        return view('property.properties.index');
    }


    public function propertyLease()
    {
        return view('property.properties.lease');
    }



    public function create()
    {
        $property = new Property();
        $property->is_active = 1;
        $property_purposes = PropertyHelper::getAllPropertyPurposes();

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
            'name' => 'required',
            'max_amount' => 'required',
            'min_amount' => 'required',
            'provider' => 'required',
            'description' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $meeting = $this->serviceTypeService->findServiceTypeById($request->input("id"));
            $results = $this->serviceTypeService->updateServiceType($data, $meeting);
        }else{
            $results = $this->serviceTypeService->createServiceType($data);
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
            return view('service-types.show', compact('property'));
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
        $service_type = $this->serviceTypeService->findServiceTypeById($id);

        if ($request->ajax()){
            return view('service-types.edit', compact('service_type'));
        }

        return view('service-types.create', compact('service_type'));
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
        $meeting = $this->serviceTypeService->findServiceTypeById($id);
        $result = $this->serviceTypeService->deleteServiceType($meeting);

        return $this->responseJson($result);
    }
}
