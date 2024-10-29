<?php

namespace App\Http\Controllers\Configuration\Leave;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Timesheet\LeaveTypeDetail;
use App\Services\Interfaces\ILeaveTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LeaveTypeDetailController extends Controller
{
    private ILeaveTypeService $leaveTypeService;

    /**
     * Create a new controller instance.
     *
     * @param ILeaveTypeService $leaveTypeService
     */
    public function __construct(ILeaveTypeService $leaveTypeService)
    {
        $this->middleware(['permission:access-variable_type']);
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $id = $request->id;
        if (request()->ajax())
        {
            return datatables()->of(LeaveTypeDetail::where('leave_type_id', $id)->get())
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('grade_name',function ($row)
                {
                    return $row->grade->name??"N/A";
                })
                ->addColumn('grade_step',function ($row)
                {
                    return $row->gradeStep->name??"N/A";
                })
                ->addColumn('employee_category_name',function ($row)
                {
                    return $row->employeeCategory->name??"N/A";
                })
                ->addColumn('employee_type_name',function ($row)
                {
                    return $row->employeeType->emp_type_name??"N/A";
                })
                ->addColumn('designation_name',function ($row)
                {
                    return $row->designation->designation_name??"N/A";
                })
                ->addColumn('department_name',function ($row)
                {
                    return $row->department->department_name??"N/A";
                })
                ->addColumn('status_name',function ($row)
                {
                    return $row->employeeStatus->emp_type_name??"N/A";
                })
                ->addColumn('location_name',function ($row)
                {
                    return $row->location->location_name??"N/A";
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-leave-types'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-leave-types'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('settings.partials.leave_type');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        if (request()->ajax())
        {
            $leave_type_detail = LeaveTypeDetail::findOrFail($id);
            $leave_type = $this->leaveTypeService->findLeaveTypeById($leave_type_detail->leave_type_id);
            $leave_type_detail->leave_type_id = $leave_type->id;
            $leave_type_detail->leave_type_name = $leave_type->deduction_name;

            if (request()->ajax()){
                return view('settings.leave_type.leave_type_detail', compact('leave_type_detail'));
            }

            return redirect()->route('leave_type.index');
        }
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $leave_type = $this->leaveTypeService->findLeaveTypeById($request->leave_type_id);
        $leave_type_detail = new LeaveTypeDetail();
        $leave_type_detail->leave_type_id = $leave_type->id;
        $leave_type_detail->leave_type_name = $leave_type->leave_type_name;
        if (request()->ajax())
        {
            return view('configuration.leave-types.leave_type_detail', compact('leave_type_detail'));
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $id = $request->get('hidden_leave_type_detail_id');
        $validator = Validator::make($request->except('_token', '_method'),
            [
                'allocated_days' => 'required',
            ]
        );

        $data = $request->except('_token', '_method');
        $data['is_active'] = $request->get('status');

        if($request->get('employee_category'))
        {
            $data['employee_category_id'] = $request->get('employee_category');
        }
        if($request->get('employee_type'))
        {
            $data['employee_type_id'] = $request->get('employee_type');
        }
        if($request->get('grade'))
        {
            $data['grade_id'] = $request->get('grade');
        }
        if($request->get('grade_step'))
        {
            $data['grade_step_id'] = $request->get('grade_step');
        }
        if($request->get('location'))
        {
            $data['location_id'] = $request->get('location');
        }
        if($request->get('department'))
        {
            $data['department_id'] = $request->get('department');
        }
        if($request->get('designation'))
        {
            $data['designation_id'] = $request->get('designation');
        }

        $leaveType = $this->leaveTypeService->findLeaveTypeById($data['leave_type_id']);

        $results = $this->leaveTypeService->createUpdateLeaveTypeDetails($data, $leaveType);

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('leave-type.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $result = $this->leaveTypeService->deleteLeaveTypeDetail($id);

        return $this->responseJson($result);
    }
}
