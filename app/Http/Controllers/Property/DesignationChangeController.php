<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\DesignationChange;
use App\Services\Interfaces\IDesignationChangeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DesignationChangeController extends Controller
{
    private IDesignationChangeService $designationChangeService;

    /**
     * Create a new controller instance.
     *
     * @param IDesignationChangeService $promotionService
     */
    public function __construct(IDesignationChangeService $promotionService)
    {
        $this->middleware(['permission:create-designation-changes|update-designation-changes'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delete-designation-changes'], ['only' => ['destroy', 'bulkDelete']]);

        $this->designationChangeService = $promotionService;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
		$promotions = $this->designationChangeService->listDesignationChanges();
        if (request()->ajax())
        {
            return datatables()->of($promotions)
                ->setRowId(function ($promotion)
                {
                    return $promotion->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->fullname;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('old_designation_name', function ($row)
                {
                    return $row->old_designation->designation_name;
                })
                ->addColumn('new_designation_name', function ($row)
                {
                    return $row->new_designation->designation_name;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-designation-changes'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-designation-changes'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.designation-changes.index');
	}


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create() // Update the method name
    {
        $designation_change = new DesignationChange();
        if (request()->ajax()){
            return view('property.designation-changes.edit', compact('designation_change'));
        }

        return redirect()->route("property.designation-changes.index");
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
            'employee_id' => 'required',
            'old_designation' => 'required',
            'new_designation' => 'required',
            'change_date' => 'required'
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['old_designation_id'] = $request->old_designation;
        $data['new_designation_id'] = $request->new_designation;

        if ($request->has("id") && $request->input("id") != null)
        {
            $award = $this->designationChangeService->findDesignationChangeById($request->input("id"));
            $results = $this->designationChangeService->updateDesignationChange($data, $award);
        }else{
            $results = $this->designationChangeService->createDesignationChange($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('designation-change.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function edit($id)
	{
        $designation_change = $this->designationChangeService->findDesignationChangeById($id);
        if (request()->ajax()){
            return view('property.designation-changes.edit', compact('designation_change'));
        }

        return redirect()->route("property.designation-changes.index");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy($id)
	{
        $promotion = $this->designationChangeService->findDesignationChangeById($id);
        $result = $this->designationChangeService->deleteDesignationChange($promotion);

        return $this->responseJson($result);
	}

	public function bulkDelete(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('delete-promotions'))
		{
			$promotion_id = $request['promotionIdArray'];
			$promotion = DesignationChange::whereIn('id', $promotion_id);
			if ($promotion->delete())
			{
				return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Promotion')])]);
			} else
			{
				return response()->json(['error' => 'Error, selected promotions can not be deleted']);
			}
		}
		return response()->json(['success' => __('You are not authorized')]);
	}
}
