<?php

namespace App\Http\Controllers\Timesheet;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Services\Interfaces\ILeaveService;
use App\Traits\LeaveTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    use LeaveTrait;

    private ILeaveService $leaveService;

    /**
     * Create a new controller instance.
     *
     * @param ILeaveService $leaveService
     */
    public function __construct(ILeaveService $leaveService)
    {
        $this->middleware(['permission:read-leaves'], ['only' => ['employeeLeaves']]);
        $this->middleware(['permission:delete-leaves'], ['only' => ['destroy', 'bulkDelete']]);
//        $this->middleware(['permission:view-property-types'], ['only' => ['index', 'show']]);

        $this->leaveService = $leaveService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['employee_id'] = user()->id;
        $leaves = $this->leaveService->listLeaves($data);

        if (request()->ajax())
        {
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
                    $button = '<a href="'.route("timesheet.leaves.show", $data->id).'" class="dt-show btn btn-info btn-sm" data-placement="top" title="show"><i class="las la-eye"></i></a>';
                    $button .= '&nbsp;';
                    if ((user_id() == $data->employee_id && $data->status == "submitted"))
                    {
                        $button .= '<a href="'.route("timesheet.leaves.edit", $data->id).'" class="dt-show btn btn-primary btn-sm" data-placement="top" title="edit"><i class="las la-edit"></i></a>';
                        $button .= '&nbsp;';
                    }
                    if ((user_id() == $data->employee_id && $data->status == "submitted"))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('timesheet.leaves.index');
    }

    public function employeeLeaves(Request $request)
    {
        $data = $request->all();
        if (request()->ajax())
        {
            $leaves = $this->leaveService->listLeaves($data);
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
                ->addColumn('action', function ($data)
                {
                    $button = '<a href="'.route("timesheet.leaves.show", $data->id).'" class="dt-show btn btn-info btn-sm" data-placement="top" title="show"><i class="las la-eye"></i></a>';
                    $button .= '&nbsp;';
                    if (user()->can('update-leaves'))
                    {
                        $button .= '<a href="'.route("timesheet.leaves.edit", $data->id).'" class="dt-show btn btn-primary btn-sm" data-placement="top" title="edit"><i class="las la-edit"></i></a>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-leaves'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('timesheet.leaves.all');
    }

    public function create(Request $request)
    {
        $data = $this->leaveService->getCreateLeave($request->all());
        if (request()->ajax())
        {
            return view('timesheet.leaves.edit', $data);
        }

        return view('timesheet.leaves.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'leave_type' => 'required',
            'employee_id' => 'sometimes|required',
            'start_date' => 'required',
            'end_date' => 'required',
            'resumption_date' => 'required',
            'total_days' => 'required',
            'reliever_id' => 'required',
            'status' => 'sometimes|required'
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['leave_type_id'] = $data['leave_type'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $leave = $this->leaveService->findLeaveById($request->input("id"));
            $results = $this->leaveService->updateLeave($data, $leave);
        }else{
            $results = $this->leaveService->createLeave($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        return redirect()->route('timesheet.leaves.index')->with('message', $results->message);
    }

    public function show($id)
    {
        $data = $this->leaveService->getCreateLeave(['leave_id' => $id]);
        return view('timesheet.leaves.show', $data);
    }

    public function edit($id)
    {
        $data = $this->leaveService->getCreateLeave(['leave_id' => $id]);

        if(in_array($data['leave']->status, ['approved', 'rejected', 'declined']))
        {
            return redirect()->route("leaves.index")->with('error', "Leave already processed.");
        }

        return view('timesheet.leaves.edit', $data);
    }

    public function destroy($id)
    {
        $data = $this->leaveService->findLeaveById($id);
        $result = $this->leaveService->deleteLeave($data);

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

    /**
     * @param Request $request
     * @return mixed
     */
    public function checkForHolidayOrWeekend1(Request $request){
        $startDate = Carbon::createFromFormat(env('Date_Format'), $request->get('start_date'));
        $endDate = $startDate->addWeekdays($request->get('total_days')-1);
        $resumptionDate = $endDate->addWeekdays(1);

        return response()->json(['end_date' => $endDate->format('Y-m-d'), 'resumption_date' => $resumptionDate->format('Y-m-d')]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function holidayOrWeekend(Request $request)
    {
        return $this->checkForHolidayOrWeekend($request->get('start_date'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getLeaveEndDate(Request $request)
    {
        $data = $request->all();
        return $this->getEndDate($data['start_date'], $data['duration'], $data['leave_type_id']);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getLeaveResumeDate(Request $request)
    {
        $data = $request->all();
        return $this->getResumeDate($data['end_date']);
    }


    public function calendarableDetails($id)
    {
        if (request()->ajax())
        {
            $data = Leave::with('company:id,company_name',
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
