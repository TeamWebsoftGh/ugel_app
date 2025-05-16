<?php

namespace App\Http\Controllers\Resource;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Resource\KnowledgeBase;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\IKnowledgeBaseService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    /**
     * @var ICategoryService
     */
    private ICategoryService $categoryService;
    private IKnowledgeBaseService $knowledgeBaseService;

    /**
     * CategoryController constructor.
     *
     * @param ICategoryService $category
     * @param IKnowledgeBaseService $knowledgeBase
     */
    public function __construct(ICategoryService $category, IKnowledgeBaseService $knowledgeBase)
    {
        $this->categoryService = $category;
        $this->knowledgeBaseService = $knowledgeBase;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = $this->knowledgeBaseService->listTopics($request->all());
            return datatables()->of($items)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('category_name', fn($row) => $row->category->name)
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "knowledge-bases"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('resource.knowledge-base.index');
    }

    public function all(Request $request)
    {
        $topics = $this->knowledgeBaseService->listTopics($request->all());
        if (request()->ajax())
        {
            return datatables()->of($topics)
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
        return view('resource.knowledge-base.index', compact('topics'));
    }

    public function create(Request $request)
    {
        $topic = new KnowledgeBase();
        $topic->is_active = 1;
        $topic->publish_date = Carbon::now()->format('Y-m-d');
        $categories = $this->categoryService->listActiveCategories();

        if ($request->ajax()){
            return view('resource.knowledge-base.edit', compact('topic', 'categories'));
        }

        return redirect()->route("resource.knowledge-base.index");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
//            'kb_files' => 'max:2048|mimes:jpg,bmp,png,pdf,docx'
            'attachments' => 'nullable|max:10240'
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $role = $this->knowledgeBaseService->findTopicById($request->input("id"));
            $results = $this->knowledgeBaseService->updateTopic($data, $role);
        }else{
            $results = $this->knowledgeBaseService->createTopic($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('resource.knowledge-base.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $topic = $this->knowledgeBaseService->findTopicById($id);
        $categories = $this->categoryService->listActiveCategories();

        if ($request->ajax()){
            return view('resource.knowledge-base.edit', compact('topic', 'categories'));
        }

        return redirect()->route("resource.knowledge-base.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $topic = $this->knowledgeBaseService->findTopicById($id);
        $files = $topic->documents()->orderByDesc('created_at')->get();

        return view("resource.knowledge-base.show", compact("topic", "files"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $item = $this->knowledgeBaseService->findTopicById($id);
        $result = $this->knowledgeBaseService->deleteTopic($item);

        return $this->responseJson($result);
    }

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->knowledgeBaseService->deleteMultiple($request->ids);
        return $this->responseJson($result);
    }

}
