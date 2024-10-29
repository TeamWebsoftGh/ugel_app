<?php

namespace App\Http\Controllers\Timesheet;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Common\FinancialYear;
use App\Models\Timesheet\LeaveType;
use App\Services\Interfaces\ILeaveService;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    private ILeaveService $leaveService;

    /**
     * Create a new controller instance.
     *
     * @param ILeaveService $leaveService
     */
    public function __construct(ILeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function index(Request $request)
    {
        $leave_types = LeaveType::select('id', 'leave_type_name', 'allocated_days')->get();
        $leave_type = $leave_types->first();
        $leave_year = date('Y');

        if($request->has('leave_type') && $request->leave_type != null)
        {
            $leave_type = LeaveType::find($request->leave_type);
        }

        $balances =  $this->leaveService->listLeaveBalances($leave_type->id);

        if($request->has('leave_year') && $request->filter_leave_year != null)
        {
            $balances = $balances->where('leave_year', '==', $request->filter_leave_year);
        }else{
            $balances = $balances->where('leave_year', '==', $leave_year);
        }

        if (request()->ajax())
        {
            return datatables()->of($balances)
                ->setRowId(function ($leave)
                {
                    return $leave->id;
                })
                ->addColumn('leave_type', function ($row)
                {
                    return $row->LeaveType->leave_type_name ?? '';
                })
                ->addColumn('department', function ($row)
                {
                    return $row->employee->department->department_name ?? '';
                })
                ->addColumn('employee', function ($row)
                {
                    return $row->employee->full_name ?? '';
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id ?? '';
                })
                ->make(true);
        }

        return view('timesheet.leave-balances.index', compact('leave_types', 'leave_type', 'leave_year'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'leave_type' => 'required',
        ]);

        $data = $request->except('_token', '_method');

        if(empty($data['leave_year']))
        {
            $data['leave_year'] = date('Y');
        }

        $results = $this->leaveService->createUpdateLeaveBalances($data);

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('timesheet.leave-balances.index');
    }
}
