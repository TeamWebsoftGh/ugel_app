<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\Complaint;
use App\Services\Interfaces\IComplaintService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    private IComplaintService $complaintService;

    /**
     * Create a new controller instance.
     *
     * @param IComplaintService $complaint
     */
    public function __construct(IComplaintService $complaint)
    {
        parent::__construct();
        $this->complaintService = $complaint;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
     */
	public function index()
	{
		$complaints = $this->complaintService->listComplaints('updated_at', 'desc');
		$complaint = new Complaint();

        if (request()->ajax())
        {
            return datatables()->of($complaints)
                ->setRowId(function ($complaint)
                {
                    return $complaint->id;
                })
                ->addColumn('complaint_from', function ($row)
                {
                    return $row->complaint_from_employee->full_name;
                })
                ->addColumn('complaint_from_department', function ($row)
                {
                    return $row->complaint_from_employee->department->department_name;
                })
                ->addColumn('complaint_against', function ($row)
                {
                    return $row->complaint_against_employee->full_name;
                })
                ->addColumn('complaint_against_department', function ($row)
                {
                    return $row->complaint_against_employee->department->department_name;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-complaints'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-complaints'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.complaint.index', compact('complaint'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        $complaint = new Complaint();

        if (request()->ajax()){
            return view('property.complaint.edit', compact('complaint'));
        }

        return redirect()->route("property.complaints.index");
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
            'complaint_title' => 'required',
            'complaint_date' => 'required',
            'complaint_from' => 'required',
            'complaint_against' => 'required'
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $complaint = $this->complaintService->findComplaintById($request->input("id"));
            $results = $this->complaintService->updateComplaint($data, $complaint);
        }else{
            $results = $this->complaintService->createComplaint($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property.complaints.index');
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
        $complaint = $this->complaintService->findComplaintById($id);

        if (request()->ajax()){
            return view('property.complaint.edit', compact('complaint'));
        }

        return redirect()->route("property.complaint.index");
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
