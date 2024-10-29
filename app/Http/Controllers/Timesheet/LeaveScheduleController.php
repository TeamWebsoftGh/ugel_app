<?php

namespace App\Http\Controllers\Timesheet;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Timesheet\Leave;
use App\Models\Timesheet\LeaveSchedule;
use App\Services\Interfaces\ILeaveScheduleService;
use App\Traits\LeaveTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveScheduleController extends Controller
{
    use LeaveTrait;

    private ILeaveScheduleService $leaveService;

    /**
     * Create a new controller instance.
     *
     * @param ILeaveScheduleService $leaveService
     */
    public function __construct(ILeaveScheduleService $leaveService)
    {
        $this->middleware(['permission:read-leaves'], ['only' => ['employeeLeaves']]);
        $this->middleware(['permission:delete-leaves'], ['only' => ['destroy', 'bulkDelete']]);
//        $this->middleware(['permission:view-property-types'], ['only' => ['index', 'show']]);

        $this->leaveService = $leaveService;
    }

    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $leaves = $this->leaveService->listLeaveSchedules($data);
            return datatables()->of($leaves)
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
                    return $row->department->department_name ?? '';
                })
                ->addColumn('employee', function ($row)
                {
                    return $row->employee->full_name ?? '';
                })
                ->addColumn('staff_id', function ($row)
                {
                    return $row->employee->staff_id ?? '';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-leave-schedules'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-leave-schedules'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('timesheet.leave-schedules.index');
    }

    public function create(Request $request)
    {
        $leave_schedule = new LeaveSchedule();
        $employee = employee();
        $leave_types = $this->getLeaveTypes($employee);
        $show_employees = user()->can('create-leaves');
        if (request()->ajax()){
            return view('timesheet.leave-schedules.edit',
                compact("leave_schedule", "employee", "leave_types","show_employees"));
        }

        return redirect()->route("timesheet.leave-schedules.index");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'leave_type' => 'required',
            'employee_id' => 'sometimes|required',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'resumption_date' => 'required',
            'total_days' => 'required',
            'status' => 'sometimes|required'
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['leave_type_id'] = $data['leave_type'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $leave = $this->leaveService->findLeaveScheduleById($request->input("id"));
            $results = $this->leaveService->updateLeaveSchedule($data, $leave);
        }else{
            $results = $this->leaveService->createLeaveSchedule($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        return redirect()->route('timesheet.leave-schedules.index')->with('message', $results->message);
    }

    public function edit($id)
    {
        $leave_schedule = $this->leaveService->findLeaveScheduleById($id);
        $employee = $leave_schedule->employee;
        $leave_types = $this->getLeaveTypes($employee);
        $show_employees = user()->can('create-leaves');
        if (request()->ajax()){
            return view('timesheet.leave-schedules.edit',
                compact("leave_schedule", "employee", "leave_types","show_employees"));
        }

        return redirect()->route("timesheet.leave-schedules.index");
    }

    public function destroy($id)
    {
        $data = $this->leaveService->findLeaveScheduleById($id);
        $result = $this->leaveService->deleteLeaveSchedule($data);

        return $this->responseJson($result);
    }

    public function bulkDelete(Request $request)
    {
        $logged_user = auth()->user();

        if ($logged_user->can('delete-leave'))
        {

            $leave_id = $request['leaveIdArray'];
            $leave = leave::whereIn('id', $leave_id);
            if ($leave->delete())
            {
                return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Leave')])]);
            } else
            {
                return response()->json(['error' => 'Error, selected leaves can not be deleted']);
            }
        }

        return response()->json(['success' => __('You are not authorized')]);
    }


    public function calendarableDetails($id)
    {
        if (request()->ajax())
        {
            $data = LeaveSchedule::with('company:id,company_name',
                'LeaveType:id,leave_type_name', 'employee:id,first_name,last_name')->findOrFail($id);

            $new = [];

            $new['Company'] = $data->company->company_name;
            $new['Employee'] = $data->employee->full_name;
            $new['Arrangement Type'] = $data->LeaveType->leave_type_name;
            $new['Start Date'] = $data->start_date;
            $new['End Date'] = $data->end_date;
            $new['Leave Reason'] = $data->leave_reason;
            $new['Remarks'] = $data->remarks;
            $new['Status'] = 'Approved';

            return response()->json(['data' => $new]);
        }
    }
}
