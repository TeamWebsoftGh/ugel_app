<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Http\Requests\ImportRequest;
use App\Imports\AmenitiesImport;
use App\Models\Property\Property;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyUnit;
use App\Models\Property\Room;
use App\Services\Properties\Interfaces\IPropertyTypeService;
use App\Services\Properties\Interfaces\IRoomService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private IRoomService $roomService;

    /**
     * Create a new controller instance.
     *
     * @param IRoomService $room
     */
    public function __construct(IRoomService $room)
    {
        parent::__construct();
        $this->roomService = $room;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $data = request()->all();
        if (request()->ajax())
        {
            $types = $this->roomService->listRooms($data);

            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('property_name', function ($row)
                {
                    return $row->propertyUnit->property->property_name ?? '';
                })
                ->addColumn('property_unit_name', function ($row)
                {
                    return $row->propertyUnit->unit_name ?? '';
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

        return view('property.rooms.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View
     */
    public function create()
    {
        $room = new Room();
        $properties = Property::select('id', 'property_name')->get();

        if (request()->ajax()){
            return view('property.rooms.edit', compact('room', 'properties'));
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
            'room_name' => 'required',
            'property_unit_id' => 'required',
            'is_active' => 'sometimes',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $property_type = $this->roomService->findRoomById($request->input("id"));
            $results = $this->roomService->updateRoom($data, $property_type);
        }else{
            $results = $this->roomService->createRoom($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('rooms.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function show($id)
	{
        $property_type = $this->roomService->findRoomById($id);
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
        $room = $this->roomService->findRoomById($id);
        $properties = Property::select('id', 'property_name')->get();

        if (request()->ajax()){
            return view('property.rooms.edit', compact('room', 'properties'));
        }

        return redirect()->route("rooms.index");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(int $id)
	{
        $award = $this->roomService->findRoomById($id);
        $result = $this->roomService->deleteRoom($award);

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
        $result = $this->propertyTypeService->deleteMultiplePropertyTypes($request->ids);
        return $this->responseJson($result);
    }

    /**
     * Show the import view.
     *
     * @return View|Factory|\Illuminate\Foundation\Application
     */
    public function import()
    {
        return view('property.property-types.import');
    }

    /**
     * Handle import of amenities.
     *
     * @return RedirectResponse
     */
    public function importPost(ImportRequest $request)
    {
        $result = $this->importExcel(new AmenitiesImport(), $request, "property types");

        if(isset($result->data) && $result->status == ResponseType::ERROR)
        {
            return view('shared.importError', ['failures' => $result->data]);
        }

        if ($request->ajax()) {
            return $this->responseJson($result);
        }

        return $this->handleRedirect($result, 'property-types.index');
    }
}
