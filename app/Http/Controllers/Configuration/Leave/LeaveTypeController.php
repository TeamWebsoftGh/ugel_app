<?php

namespace App\Http\Controllers\Configuration\Leave;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Timesheet\LeaveType;
use App\Services\Interfaces\ILeaveTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


class LeaveTypeController extends Controller
{
    private ILeaveTypeService $leaveTypeService;

    /**
     * Create a new controller instance.
     *
     * @param ILeaveTypeService $leaveTypeService
     */
    public function __construct(ILeaveTypeService $leaveTypeService)
    {
        parent::__construct();
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function index()
    {
        $leave_types = $this->leaveTypeService->listLeaveTypes();

        if (request()->ajax())
        {
            return datatables()->of($leave_types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<a href="'.route("configuration.leave-types.show", $data->id).'" class="dt-show btn btn-primary btn-sm" data-placement="top" title="show"><i class="las la-eye"></i></a>';
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
        return view('configuration.leave-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function create()
    {
        $leave_type = new LeaveType();

        if (request()->ajax())
        {
            return view('configuration.leave-types.edit', compact('leave_type'));
        }
        return redirect()->route('leave-type.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request)
    {
        $id = $request->get('hidden_leave_type_id');

        $validatedData = $request->validate([
            'leave_type_name' => 'required|unique:pay_benefits,id,'.$id,
            'leave_category' => 'required',
            'status' => 'required',
            'allocated_days' => 'required',
            'can_accumulate' => 'required',
            'working_days_only' => 'required',
            'pay_percentage' => 'nullable',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['is_active'] = $data['status'];

        if ($request->has("id") && $request->input("id") != null)
        {
            $warning = $this->leaveTypeService->findLeaveTypeById($request->input("id"));
            $results = $this->leaveTypeService->updateLeaveType($data, $warning);
        }else{
            $data['company_id'] = user()->company_id;
            $results = $this->leaveTypeService->createLeaveType($data);
        }

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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function edit($id)
    {
        $leave_type = $this->leaveTypeService->findLeaveTypeById($id);

        if (request()->ajax())
        {
            return view('configuration.leave-types.edit', compact('leave_type'));
        }
        return redirect()->route('leave-type.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $leave_type = $this->leaveTypeService->findLeaveTypeById($id);

        return view('configuration.leave-types.show', compact('leave_type'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $data = $this->leaveTypeService->findLeaveTypeById($id);
        $result = $this->leaveTypeService->deleteLeaveType($data);

        return $this->responseJson($result);
    }
}
