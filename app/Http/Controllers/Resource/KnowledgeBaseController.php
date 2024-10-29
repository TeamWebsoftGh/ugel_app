<?php

namespace App\Http\Controllers\Resource;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Resource\KnowledgeBase;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\IKnowledgeBaseService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    use JsonResponseTrait;
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

    public function index()
    {
        $topics = $this->knowledgeBaseService->listTopics('updated_at');
        $categories = $this->categoryService->listActiveCategories();
        $topic = new KnowledgeBase();
        return view('resource.knowledge-base.create', compact('topics', 'topic', 'categories'));
    }

    public function showAll()
    {
        $topics = $this->knowledgeBaseService->listTopics('created_at', 'asc');
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
            'kb_files' => 'nullable|max:10240'
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
        $files = $topic->documents()->orderByDesc('created_at')->get();

        if ($request->ajax()){
            return view('resource.knowledge-base.edit', compact('topic', 'categories', 'files'));
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

    public function deleteDocument($topic_id, $id)
    {
        $topic = $this->knowledgeBaseService->findTopicById($topic_id);

        $document = $topic->documents()->findOrFail($id);

        $result = $this->knowledgeBaseService->deleteDocument($document, $topic);
        return $this->responseJson($result);
    }

}
