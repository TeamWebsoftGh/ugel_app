<?php

namespace App\Http\Controllers\Memo;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Memo\Event;
use App\Services\Interfaces\IEventService;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IEventService
     */
    private IEventService $eventService;

    /**
     * CategoryController constructor.
     *
     * @param IEventService $eventService
     */
    public function __construct(IEventService $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware(['permission:create-events'], ['only' => ['update', 'edit', 'create']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $events = $this->eventService->listEvents('updated_at');
        if (empty($data['filter_start_date']))
        {
            $data['start_date'] = Carbon::now()->startOfYear()->format(env('Date_Format'));
            $data['filter_start_date'] = Carbon::now()->startOfYear()->format('Y-m-d');
        }else{
            $data['start_date'] = Carbon::parse($data['filter_start_date'])->format(env('Date_Format'));
        }

        if (empty($data['filter_end_date']))
        {
            $data['end_date'] = Carbon::now()->format(env('Date_Format'));
            $data['filter_end_date'] = Carbon::now()->format('Y-m-d');
        }else{
            $data['end_date'] = Carbon::parse($data['filter_end_date'])->format(env('Date_Format'));
        }

        return view('memo.events.index', compact('events', 'data'));
    }


    public function create()
    {
        $event = new Event();
        $event->is_active = 1;
        return view('memo.events.create', compact('event'));
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
            $announcement = $this->eventService->findEventById($request->input("id"));
            $results = $this->eventService->updateEvent($data, $announcement);
        }else{
            $results = $this->eventService->createEvent($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('events.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Request $request, $id)
    {
        $event = $this->eventService->findEventById($id);

        if ($request->ajax()){
            return view('memo.events.show', compact('event'));
        }

        return view('memo.events.show', compact('event'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request, $id)
    {
        $event = $this->eventService->findEventById($id);

        if ($request->ajax()){
            return view('memo.events.edit', compact('event'));
        }

        return view('memo.events.create', compact('event'));
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
        $event = $this->eventService->findEventById($id);
        $result = $this->eventService->deleteEvent($event);

        return $this->responseJson($result);
    }
}
