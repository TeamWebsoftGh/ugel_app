<?php

namespace App\Http\Controllers\Memo;

use App\Constants\ResponseType;
use App\Models\Memo\Announcement;
use App\Models\Memo\Meeting;
use App\Services\Interfaces\IBulkSmsService;
use App\Services\Interfaces\IMeetingService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeetingController extends \App\Http\Controllers\Controller
{
    use JsonResponseTrait;
    /**
     * @var IMeetingService
     */
    private IMeetingService $meetingService;

    /**
     * MeetingController constructor.
     *
     * @param IMeetingService $meetingService
     */
    public function __construct(IMeetingService $meetingService)
    {
        $this->meetingService = $meetingService;
    }

    public function index()
    {
        $meetings = $this->meetingService->listMeetings('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($meetings)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->make(true);
        }
        return view('memo.meetings.index', compact('meetings'));
    }


    public function create()
    {
        $meeting = new Meeting();
        $meeting->is_active = 1;
        return view('memo.meetings.create', compact('meeting'));
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
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $meeting = $this->meetingService->findMeetingById($request->input("id"));
            $results = $this->meetingService->updateMeeting($data, $meeting);
        }else{
            $results = $this->meetingService->createMeeting($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('meetings.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Request $request, $id)
    {
        $meeting = $this->meetingService->findMeetingById($id);

        if ($request->ajax()){
            return view('memo.meetings.show', compact('meeting'));
        }

        return view('memo.meetings.show', compact('meeting'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $meeting = $this->meetingService->findMeetingById($id);

        if ($request->ajax()){
            return view('memo.meetings.edit', compact('meeting'));
        }

        return view('memo.meetings.create', compact('meeting'));
    }

    /**
     *
     * /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $meeting = $this->meetingService->findMeetingById($id);
        $result = $this->meetingService->deleteMeeting($meeting);

        return $this->responseJson($result);
    }
}
