<?php

namespace App\Http\Controllers\Timesheet;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Timesheet\Holiday;
use App\Services\Interfaces\IHolidayService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class HolidayController extends Controller
{
    private IHolidayService $holidayService;

    /**
     * CategoryController constructor.
     *
     * @param IHolidayService $holidayService
     */
    public function __construct(IHolidayService $holidayService)
    {
        parent::__construct();
        $this->holidayService = $holidayService;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $holidays = $this->holidayService->listHolidays();
        if (request()->ajax())
        {
            return datatables()->of($holidays)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?'active':'inactive';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-holidays'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-holidays'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('timesheet.holidays.index');
	}

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $holiday = new Holiday();
        if (request()->ajax()){
            return view('timesheet.holidays.edit', compact("holiday"));
        }

        return redirect()->route("timesheet.holidays.index");
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
            'event_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
          //  'is_publish' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $asset = $this->holidayService->findHolidayById($request->input("id"));
            $results = $this->holidayService->updateHoliday($data, $asset);
        }else{
            $results = $this->holidayService->createHoliday($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('timesheet.holidays.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function edit($id)
	{
        $holiday = $this->holidayService->findHolidayById($id);
		if (request()->ajax())
		{
            return view('timesheet.holidays.edit', compact("holiday"));
		}

        return redirect()->route("timesheet.holidays.index");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy($id)
	{
        $holiday = $this->holidayService->findHolidayById($id);
        $result = $this->holidayService->deleteHoliday($holiday);

        return $this->responseJson($result);
	}

	public function bulkDelete(Request $request)
	{
        $holiday_id = $request['holidayIdArray'];
        $holiday = Holiday::whereIn('id', $holiday_id);
        if ($holiday->delete())
        {
            return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Holiday')])]);
        } else
        {
            return response()->json(['error' => 'Error,selected holidays can not be deleted']);
        }
	}

	public function calendarableDetails($id)
	{
        $data = $this->holidayService->findHolidayById($id);
		if (request()->ajax())
		{
			$new = [];
			$new['Company'] = $data->company->company_name;
			$new['Event Name'] = $data->event_name;
			$new['Start Date'] = $data->start_date;
			$new['End Date'] = $data->end_date;
			$new['Description'] = $data->description;
			$new['Status'] = 'Published';

			return response()->json(['data' => $new]);
		}

        return redirect()->route("timesheet.holidays.index");
	}
}
