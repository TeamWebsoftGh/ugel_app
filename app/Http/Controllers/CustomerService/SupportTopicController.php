<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\CustomerService\SupportTopic;
use App\Services\CustomerService\Interfaces\ISupportTopicService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupportTopicController extends Controller
{
    private ISupportTopicService $topicService;

    Public function __construct(ISupportTopicService $topicService)
    {
        parent::__construct();
        $this->topicService = $topicService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|\Illuminate\Foundation\Application|View|JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if ($request->ajax()) {
            $topics = $this->topicService->listSupportTopics($data);
            return datatables()->of($topics)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "support-topics"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view("customer-service.support-topics.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $item = new SupportTopic();
        if (request()->ajax()){
            return view('customer-service.support-topics.edit', compact('item'));
        }
        return view("customer-service.support-topics.create", compact('item'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');

        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if ($request->has("id") && $request->input("id") != null)
        {
            $task = $this->topicService->findSupportTopicById($request->input("id"));
            $results = $this->topicService->updateSupportTopic($data, $task);
        }else{
            $results = $this->topicService->createSupportTopic($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message)->withInput($data);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('support-topics.show', $results->data->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function show($id)
    {
        $item= $this->topicService->findSupportTopicById($id);
        return view("customer-service.support-topics.show", compact("item"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|\Illuminate\Foundation\Application|object|View
     */
    public function edit($id)
    {
        $item = $this->topicService->findSupportTopicById($id);
        return view("customer-service.support-topics.edit", compact("item"));
    }

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->topicService->de($request->ids);
        return $this->responseJson($result);
    }


}
