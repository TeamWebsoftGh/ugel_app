<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Common\TravelType;
use App\Models\Property\Complaint;
use App\Models\Property\Travel;
use App\Services\Interfaces\ITravelService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TravelController extends Controller
{
    use JsonResponseTrait;

    private ITravelService $travelService;

    /**
     * Create a new controller instance.
     *
     * @param ITravelService $travel
     */
    public function __construct(ITravelService $travel)
    {
        $this->middleware(['permission:create-travels|update-travels'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delete-travels'], ['only' => ['destroy', 'bulkDelete']]);
        $this->middleware(['permission:read-travels'], ['only' => ['index', 'show']]);

        $this->travelService = $travel;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $travels = $this->travelService->listTravels($data);

        if (request()->ajax())
        {
            return datatables()->of($travels)
                ->setRowId(function ($travel)
                {
                    return $travel->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->full_name;
                })
                ->addColumn('travel_type_name', function ($row)
                {
                    return $row->travel_type->arrangement_type;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-travels'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-travels'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.travels.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        $travel = new Travel();
        $travel_types = TravelType::select('id', 'arrangement_type')->get();
        if (request()->ajax()){
            return view('property.travels.edit', compact('travel', 'travel_types'));
        }

        return redirect()->route("property.travels.index");
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
     */
	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'employee_id' => 'required',
            'travel_type_id' => 'required',
            'place_of_visit' => 'required',
            'purpose_of_visit' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['travel_type'] = $request->arrangement_type;

        if ($request->has("id") && $request->input("id") != null)
        {
            $travel = $this->travelService->findTravelById($request->input("id"));
            $results = $this->travelService->updateTravel($data, $travel);
        }else{
            $data['company_id'] = user()->company_id;
            $results = $this->travelService->createTravel($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('travels.index');
	}


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $complaint = $this->complaintService->findComplaintById($id);

        if (request()->ajax()){
            return view('property.complaint.edit', compact('complaint'));
        }

        return redirect()->route("property.complaint.index");
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function edit($id)
	{
        $travel = $this->travelService->findTravelById($id);
        $travel_types = TravelType::select('id', 'arrangement_type')->get();

        if (request()->ajax()){
            return view('property.travels.edit', compact('travel', 'travel_types'));
        }

        return redirect()->route("property.travels.index");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return JsonResponse
     */
	public function destroy($id)
	{
        $complaint = $this->complaintService->findComplaintById($id);

        $result = $this->complaintService->deleteComplaint($complaint);

        return $this->responseJson($result);
	}

	public function delete_by_selection(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('delete-complaint'))
		{

			$complaint_id = $request['complaintIdArray'];
			$complaint = Complaint::whereIn('id', $complaint_id);
			if ($complaint->delete())
			{
				return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Complaint')])]);
			} else
			{
				return response()->json(['error' => 'Error, selected complaints can not be deleted']);
			}
		}
		return response()->json(['success' => __('You are not authorized')]);
	}
}
