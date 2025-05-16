<?php

namespace App\Http\Controllers\Communication;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Communication\Announcement;
use App\Models\Communication\ContactGroup;
use App\Models\Communication\SmsAlert;
use App\Services\Interfaces\IBulkSmsService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class BulkSmsController extends Controller
{
    use SmsTrait;
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
        $data['filter_start_date'] = $request->get('filter_start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $data['filter_end_date'] = $request->get('filter_end_date', Carbon::now()->format('Y-m-d'));
        $data['filter_property'] = $request->get('filter_property', '');
        $data['filter_client_type'] = $request->get('filter_client_type', '');
        $data['filter_property_type'] = $request->get('filter_property_type', '');
        $data['filter_type'] = "sms";

        if (request()->ajax())
        {
            $announcements = $this->announcementService->listAnnouncements($data);
            return datatables()->of($announcements)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('property_type_name', function ($row)
                {
                    return $row->propertyType->name ?? 'All';
                })
                ->addColumn('property_name', function ($row)
                {
                    return $row->property->property_name ?? 'All';
                })
                ->addColumn('client_type_name', function ($row)
                {
                    return $row->clientType->name ?? 'All';
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-bulk-sms'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-bulk-sms'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'document'])
                ->make(true);
        }

        return view('communication.bulk-sms.index', compact('data'));
    }

    public function create()
    {
        $announcement = new Announcement();
        $announcement->is_active = 1;

        if (request()->ajax()){
            return view('communication.bulk-sms.edit', compact("announcement"));
        }
        return view('communication.bulk-sms.create', compact('announcement'));
    }

    public function quickSms()
    {
        $sms = new SmsAlert();
        $sms->is_active = 1;
        $contact_groups = ContactGroup::where('is_active', 1)->get();

        return view('communication.bulk-sms.quick', compact('sms', 'contact_groups'));
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
            session()->flash('error', $results->message);
            return redirect()->back()->withInput($data);
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
            return view('communication.bulk-sms.show', compact('announcement'));
        }

        return view('communication.bulk-sms.show', compact('announcement'));

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
            return view('communication.bulk-sms.edit', compact('announcement'));
        }

        return view('communication.bulk-sms.create', compact('announcement'));
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
