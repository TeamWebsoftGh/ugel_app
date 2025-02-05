<?php

namespace App\Http\Controllers\Memo;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Memo\Announcement;
use App\Models\Memo\ContactGroup;
use App\Models\Memo\SmsAlert;
use App\Services\Interfaces\IBulkSmsService;
use App\Traits\JsonResponseTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BulkSmsController extends Controller
{
    use JsonResponseTrait, SmsTrait;
    /**
     * @var IBulkSmsService
     */
    private IBulkSmsService $announcementService;

    /**
     * CategoryController constructor.
     *
     * @param IBulkSmsService $announcement
     */
    public function __construct(IBulkSmsService $announcement)
    {
        parent::__construct();
        $this->announcementService = $announcement;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_type'] = "sms";
        $announcements = $this->announcementService->listAnnouncements($data);

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

        return view('memo.announcements.index', compact('announcements', 'data'));
    }


    public function create()
    {
        $announcement = new Announcement();
        $announcement->is_active = 1;
        return view('memo.announcements.create', compact('announcement'));
    }

    public function quickSms()
    {
        $sms = new SmsAlert();
        $sms->is_active = 1;
        $contact_groups = ContactGroup::where('is_active', 1)->get();

        return view('memo.announcements.quick', compact('sms', 'contact_groups'));
    }

    public function quickSmsPost(Request $request)
    {
        $validatedData =  $request->validate([
            'short_message' => 'required|max:160',
            'contact_group_id' => 'required_without:recipient',
            'recipient' => 'required_without:contact_group_id',
        ]);

        $data = $request->all();

        $data['type'] = "sms";
        $results = $this->announcementService->sendQuickSms($data);

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        session()->flash('message', $results->message);

        return redirect()->route('bulk-sms.quick');

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
            'short_message' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['type'] = "sms";

        if ($request->has("id") && $request->input("id") != null)
        {
            $announcement = $this->announcementService->findAnnouncementById($request->input("id"));
            $results = $this->announcementService->updateAnnouncement($data, $announcement);
        }else{
            $results = $this->announcementService->createAnnouncement($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        session()->flash('message', $results->message);

        return redirect()->route('bulk-sms.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|Response
     */
    public function show(Request $request, $id)
    {
        $announcement = $this->announcementService->findAnnouncementById($id);

        if ($request->ajax()){
            return view('memo.announcements.show', compact('announcement'));
        }

        return view('memo.announcements.show', compact('announcement'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request, $id)
    {
        $announcement = $this->announcementService->findAnnouncementById($id);

        if ($request->ajax()){
            return view('memo.announcements.edit', compact('announcement'));
        }

        return view('memo.announcements.create', compact('announcement'));
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
        $announcement = $this->announcementService->findAnnouncementById($id);
        $result = $this->announcementService->deleteAnnouncement($announcement);

        return $this->responseJson($result);
    }
}
