<?php

namespace App\Http\Controllers\Communication;

use App\Constants\ResponseType;
use App\Models\Communication\Announcement;
use App\Models\Communication\ContactGroup;
use App\Models\Communication\SmsAlert;
use App\Services\Interfaces\IBulkSmsService;
use App\Traits\JsonResponseTrait;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class WhatsappController extends \App\Http\Controllers\Controller
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
        $data['filter_type'] = 'whatsapp';
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

        return view('communication.whatsapp.index', compact('announcements', 'data'));
    }


    public function create()
    {
        $announcement = new Announcement();
        $announcement->is_active = 1;
        $tem_types = settings("whatsapp_templates");
        $tem_types = !empty($tem_types) ? array_map('trim', explode(',', $tem_types)) : [];

        return view('communication.whatsapp.create', compact('announcement', 'tem_types'));
    }

    public function quickWhatsApp()
    {
        //dd( $this->sendWhatAppMessages("+233242734804", "Testing 123"));
        $sms = new SmsAlert();
        $sms->is_active = 1;
        $sms->type = "whatsapp";
        $tem_types = settings("whatsapp_templates");
        $tem_types = !empty($tem_types) ? array_map('trim', explode(',', $tem_types)) : [];

        $contact_groups = ContactGroup::where('is_active', 1)->get();

        return view('communication.whatsapp.quick', compact('sms', 'contact_groups', 'tem_types'));
    }

    public function quickWhatsAppPost(Request $request)
    {
        $validatedData = $request->validate([
            'file_type' => 'nullable|in:audio,image,document,video',
            'file' => 'required_without:tem_type|file|max:5120',
            'short_message' => 'required_if:tem_type,custom',
            'contact_group_id' => 'required_without:recipient',
            'recipient' => 'required_without:contact_group_id',
        ]);

        if ($request->hasFile('file')) {
            $fileType = $request->file_type;

            // Validate based on selected file type
            switch ($fileType) {
                case 'audio':
                    $request->validate(['file' => 'mimes:mp3,wav,m4a']);
                    break;
                case 'image':
                    $request->validate(['file' => 'mimes:jpeg,png,jpg,gif']);
                    break;
                case 'document':
                    $request->validate(['file' => 'mimes:pdf,doc,docx,xls,xlsx,txt']);
                    break;
                case 'video':
                    $request->validate(['file' => 'mimes:mp4,mov,avi,wmv']);
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid file type selected.');
            }
        }

        $data = $request->all();
        $data['type'] = "whatsapp";

        $results = $this->announcementService->sendQuickSms($data);

        if ($request->ajax()) {
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS) {
            return redirect()->back()->with('error', $results->message);
        }

        session()->flash('message', $results->message);

        return redirect()->route('whatsapp.quick');
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
            'file_type' => 'nullable|in:audio,image,document,video',
            'file' => 'required_without:tem_type|file|max:5120',
            'short_message' => 'required_if:tem_type,custom',
            ]);

        if ($request->hasFile('file')) {
            $fileType = $request->file_type;

            // Validate based on selected file type
            switch ($fileType) {
                case 'audio':
                    $request->validate(['file' => 'mimes:mp3,wav,m4a']);
                    break;
                case 'image':
                    $request->validate(['file' => 'mimes:jpeg,png,jpg,gif']);
                    break;
                case 'document':
                    $request->validate(['file' => 'mimes:pdf,doc,docx,xls,xlsx,txt']);
                    break;
                case 'video':
                    $request->validate(['file' => 'mimes:mp4,mov,avi,wmv']);
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid file type selected.');
            }
        }

        $data = $request->except('_token', '_method', 'id');
        $data['type'] = "whatsapp";

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

        return redirect()->route('whatsapp.index');
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
            return view('communication.whatsapp.show', compact('announcement'));
        }

        return view('communication.whatsapp.show', compact('announcement'));

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
            return view('communication.whatsapp.edit', compact('announcement'));
        }

        return view('communication.whatsapp.create', compact('announcement'));
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
