<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\Constants;
use App\Constants\ResponseType;
use App\Models\CustomerService\VisitorLog;
use App\Services\Interfaces\IVisitorLogService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class VisitorLogController extends Controller
{
    private IVisitorLogService $visitorLogService;

    public function __construct(IVisitorLogService $visitorLogService)
    {
        $this->visitorLogService = $visitorLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $visitor_logs = $this->visitorLogService->listVisitorLogs($data);

        if (request()->ajax())
        {
            return datatables()->of($visitor_logs)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('employee_name', function ($row)
                {
                    return $row->employee->full_name ?? '';
                })
                ->addColumn('department', function ($row)
                {
                    return $row->employee->department->department_name ?? '';
                })
                ->addColumn('visitor_name', function ($row)
                {
                    return $row->visitor->full_name ?? '';
                })
                ->addColumn('visitor_phone', function ($row)
                {
                    return $row->visitor->phone_number ?? '';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-visitor-logs'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-visitor-logs'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'document'])
                ->make(true);
        }

        return view('customer-service.visitor-logs.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $visitor_log = new VisitorLog();
        $reasons = Constants::VISITOR_REASONS;
        if (request()->ajax()){
            return view('customer-service.visitor-logs.edit', compact("visitor_log", "reasons"));
        }

        return redirect()->route("visitor-logs.index");
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
            'reason' => 'required',
            'check_in' => 'required',
            'first_name' => 'required',
            'phone_number' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $asset = $this->visitorLogService->findVisitorLogById($request->input("id"));
            $results = $this->visitorLogService->updateVisitorLog($data, $asset);
        }else{
            $results = $this->visitorLogService->createVisitorLog($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('visitor-logs.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        $visitor_log = $this->visitorLogService->findVisitorLogById($id);
        $reasons = Constants::VISITOR_REASONS;
        if (request()->ajax()){
            return view('customer-service.visitor-logs.edit', compact("visitor_log", "reasons"));
        }

        return redirect()->route("visitor-logs.index");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $enquiry = $this->enquiryService->findEnquiryById($id);
        $result = $this->enquiryService->deleteEnquiry($enquiry);

        return $this->responseJson($result);
    }
}
