<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\Transfer;
use App\Services\Interfaces\ITransferService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TransferController extends Controller
{
    private $transferService;

    /**
     * Create a new controller instance.
     *
     * @param ITransferService $transfer
     */
    public function __construct(ITransferService $transfer)
    {
        $this->middleware(['permission:create-transfers|update-transfers'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delete-transfers'], ['only' => ['destroy', 'bulkDelete']]);
        $this->transferService = $transfer;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $data = $request->all();
		$transfers = $this->transferService->listTransfers($data);

        if (request()->ajax())
        {
            return datatables()->of($transfers)
                ->setRowId(function ($transfer)
                {
                    return $transfer->id;
                })
                ->addColumn('from_department', function ($row)
                {
                    return empty($row->from_department->department_name) ? 'N/A' : $row->from_department->department_name;
                })
                ->addColumn('to_department', function ($row)
                {
                    return empty($row->to_department->department_name) ? 'N/A' : $row->to_department->department_name;
                })
                ->addColumn('from_branch_name', function ($row)
                {
                    return empty($row->from_branch->branch_name) ? 'N/A' : $row->from_branch->branch_name;
                })
                ->addColumn('to_branch_name', function ($row)
                {
                    return empty($row->to_branch->branch_name) ? 'N/A' : $row->to_branch->branch_name;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->full_name;
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-transfers'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-transfers'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.transfers.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'employee' => 'required',
            'to_department' => 'required_if:to_location,'.null,
            'to_location' => 'required_if:to_department,'.null,
            'transfer_date' => 'required',
            'notice_date' => 'required'
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['employee_id'] = $request->employee;
        $data['from_branch_id'] = $request->current_branch;
        $data['from_department_id'] = $request->current_department;
        $data['to_branch_id'] = $request->to_location;

        if (isset($request->to_department))
            $data['to_department_id'] = $request->to_department;

        if (isset($request->to_location))
            $data['to_location_id'] = $request->to_location;

        if ($request->has("id") && $request->input("id") != null)
        {
            $transfer = $this->transferService->findTransferById($request->input("id"));
            $results = $this->transferService->updateTransfer($data, $transfer);
        }else{
            $data['company_id'] = user()->company_id;
            $results = $this->transferService->createTransfer($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('transfers.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function create()
	{
        $transfer = new Transfer();

        if (request()->ajax()){
            return view('property.transfers.edit', compact('transfer'));
        }

        return redirect()->route("property.transfers.index");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
        $transfer = $this->transferService->findTransferById($id);

        if (request()->ajax()){
            return view('property.transfers.edit', compact('transfer'));
        }

        return redirect()->route("property.transfers.index");
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$logged_user = auth()->user();

		if ($logged_user->can('edit-transfer'))
		{
			$id = $request->hidden_id;

			$validator = Validator::make($request->only('description', 'company_id', 'from_department_id', 'to_department_id', 'employee_id', 'transfer_date'
			),
				[
					'company_id' => 'required',
					'from_department_id' => 'required',
					'employee_id' => 'required',
					'to_department_id' => 'required',
					'transfer_date' => 'required'
				]
			);


			if ($validator->fails())
			{
				return response()->json(['errors' => $validator->errors()->all()]);
			}


			$data = [];

			$data ['description'] = $request->description;
			$data ['transfer_date'] = $request->transfer_date;


			$data['employee_id'] = $request->employee_id;

			$data ['company_id'] = $request->company_id;

			$data['from_department_id'] = $request->from_department_id;

			$data ['to_department_id'] = $request->to_department_id;

			Transfer::find($id)->update($data);
			Employee::whereId($data['employee_id'])->update(['department_id' => $data ['to_department_id']]);

			$notifiable = User::findOrFail($data['employee_id']);

			$notifiable->notify(new EmployeeTransferNotify());

			return response()->json(['success' => __('Data is successfully updated')]);
		}

		return response()->json(['success' => __('You are not authorized')]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $transfer = $this->transferService->findTransferById($id);

        $result = $this->transferService->deleteTransfer($transfer);

        return $this->responseJson($result);
	}

	public function delete_by_selection(Request $request)
	{
        $transfer_id = $request['transferIdArray'];
        $transfer = Transfer::whereIn('id', $transfer_id);
        if ($transfer->delete())
        {
            return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Transfer')])]);
        } else
        {
            return response()->json(['error' => 'Error, selected transfers can not be deleted']);
        }
	}
}
