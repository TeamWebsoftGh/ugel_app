<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Imports\PastEmployeesImport;
use App\Models\Common\TerminationType;
use App\Models\Property\Termination;
use App\Services\Interfaces\ITerminationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class TerminationController extends Controller
{
    private ITerminationService $terminationService;

    /**
     * Create a new controller instance.
     *
     * @param ITerminationService $termination
     */
    public function __construct(ITerminationService $termination)
    {
        parent::__construct();
        $this->terminationService = $termination;
    }

	public function index(Request $request)
	{
        $data = $request->all();
        $terminations = $this->terminationService->listTerminations($data);

        if (request()->ajax())
        {
            return datatables()->of($terminations)
                ->setRowId(function ($termination)
                {
                    return $termination->id;
                })
                ->addColumn('terminated_employee', function ($row)
                {
                    return $row->employee->FullName;
                })
                ->addColumn('department_name', function ($row)
                {
                    return $row->employee->department->department_name;
                })
                ->addColumn('exit_type', function ($row)
                {
                    return $row->terminationType->termination_title;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-terminations'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-terminations'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.terminations.index');
	}


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|Response|View
     */
    public function create(Request $request)
    {
        $termination_types = TerminationType::select('id', 'termination_title')->get();
        $termination = new Termination();

        if (request()->ajax())
        {
            return view('property.terminations.edit', compact('termination', 'termination_types'));
        }
        return redirect()->route('property.employee-exits.index');
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return RedirectResponse
     */
	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'employee_id' => 'required',
            'termination_type_id' => 'required',
            'termination_date' => 'required',
            'notice_date' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $termination = $this->terminationService->findTerminationById($request->input("id"));
            $results = $this->terminationService->updateTermination($data, $termination);
        }else{
            $results = $this->terminationService->createTermination($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property.employee-exits.index');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function edit($id)
	{
        $termination_types = TerminationType::select('id', 'termination_title')->get();
        $termination = $this->terminationService->findTerminationById($id);

        if (request()->ajax())
        {
            return view('property.terminations.edit', compact('termination', 'termination_types'));
        }
        return redirect()->route('property.employee-exits.index');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $termination = $this->terminationService->findTerminationById($id);

        $result = $this->terminationService->deleteTermination($termination);

        return $this->responseJson($result);
    }

	public function delete_by_selection(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('delete-termination'))
		{

			$termination_id = $request['terminationIdArray'];
			$termination = Termination::whereIn('id', $termination_id);
			if ($termination->delete())
			{
				return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Termination')])]);
			} else
			{
				return response()->json(['error' => 'Error, selected Terminations can not be deleted']);
			}
		}
		return response()->json(['success' => __('You are not authorized')]);
	}

    public function import()
    {
        return view('property.terminations.import');
    }

    public function importPost()
    {
        try
        {
            Excel::queueImport(new PastEmployeesImport(), request()->file('file'));
        } catch (ValidationException $e)
        {
            $failures = $e->failures();

            return view('past-employees.importError', compact('failures'));
        }

        $this->setSuccessMessage(__('Imported Successfully'));

        return back();

    }
}
