<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Memo\Announcement;
use App\Models\Memo\ContactGroup;
use App\Models\Memo\SmsAlert;
use App\Services\Interfaces\IBulkSmsService;
use App\Traits\JsonResponseTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;


class BulkSmsController extends MobileController
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
        $this->announcementService = $announcement;
    }

    public function index(Request $request)
    {
        $data = $request->all();
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

    public function quickSms(Request $request)
    {
        $validatedData =  $request->validate([
            'short_message' => 'required|max:160',
            'contact_group_id' => 'required_without:recipient',
            'recipient' => 'required_without:contact_group_id',
        ]);

        $data = $request->all();

        $results = $this->announcementService->sendQuickSms($data);

        return $this->apiResponseJson($results);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
            $announcement = $this->announcementService->findAnnouncementById($request->input("id"));
            $results = $this->announcementService->updateAnnouncement($data, $announcement);
        }else{
            $results = $this->announcementService->createAnnouncement($data);
        }

        return $this->apiResponseJson($results);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $announcement = $this->announcementService->findAnnouncementById($id);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $announcement);
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
        $results = $this->announcementService->deleteAnnouncement($announcement);

        return $this->apiResponseJson($results);
    }
}
