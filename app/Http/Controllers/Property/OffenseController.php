<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Common\OffenceType;
use App\Models\Common\WarningType;
use App\Models\Property\Offense;
use App\Services\Interfaces\IOffenseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OffenseController extends Controller
{
    private IOffenseService $offenseService;

    /**
     * Create a new controller instance.
     *
     * @param IoffenseService $warning
     */
    public function __construct(IOffenseService $warning)
    {
        $this->middleware(['permission:create-offenses|update-offenses'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delete-offenses'], ['only' => ['destroy', 'bulkDelete']]);

        $this->offenseService = $warning;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
     */
	public function index(Request $request)
	{
        $data = $request->all();
		$offenses = $this->offenseService->listOffenses($data);

        if (request()->ajax())
        {
            return datatables()->of($offenses)
                ->setRowId(function ($warnings)
                {
                    return $warnings->id;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->full_name;
                })
                ->addColumn('offense_type_name', function ($row)
                {
                    return $row->offenseType->name;
                })
                ->addColumn('warning_type_name', function ($row)
                {
                    return $row->warningType->name;
                })
                ->addColumn('department_name', function ($row)
                {
                    return $row->employee->department->department_name;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-offenses'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-offenses'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.offenses.index');
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
            'offense_type_id' => 'required',
            'offense_date' => 'required',
//            'status' => 'required'
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $warning = $this->offenseService->findOffenseById($request->input("id"));
            $results = $this->offenseService->updateOffense($data, $warning);
        }else{
            $data['company_id'] = user()->company_id;
            $results = $this->offenseService->createOffense($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property.offenses.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|Response|View
	 */
	public function show($id)
	{
        if (request()->ajax())
        {
            $warning = $this->offenseService->findWarningById($id);
            $warning_types = WarningType::select('id', 'warning_title')->get();

            if (request()->ajax()){
                return view('property.warning.edit', compact('warning', 'warning_types'));
            }

            return redirect()->route("property.warning.edit", compact('user'));
        }
	}

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $warning_types = WarningType::select('id', 'name')->get();
        $offense_types = OffenceType::select('id', 'name')->get();
        $offense = new Offense();

        if (request()->ajax())
        {
            return view('property.offenses.edit', compact('offense', 'warning_types', 'offense_types'));
        }
        return redirect()->route('property.offenses.index');
    }
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		if (request()->ajax())
		{
			$data = $this->offenseService->findWarningById($id);
			$employees = Employee::select('id', 'first_name', 'last_name')->where('company_id', $data->company_id)->get();

			return response()->json(['data' => $data, 'employees' => $employees]);
		}
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
	public function destroy(int $id)
    {
        $warning = $this->offenseService->findWarningById($id);

        $result = $this->offenseService->deleteWarning($warning);

        return $this->responseJson($result);
	}

	public function delete_by_selection(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('delete-warning'))
		{

			$warning_id = $request['warningIdArray'];
			$warning = Warning::whereIn('id', $warning_id);
			if ($warning->delete())
			{
				return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Warning')])]);
			} else
			{
				return response()->json(['error' => 'Error, selected Warnings can not be deleted']);
			}
		}
		return response()->json(['success' => __('You are not authorized')]);
	}
}
