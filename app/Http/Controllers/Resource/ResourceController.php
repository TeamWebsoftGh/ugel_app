<?php

namespace App\Http\Controllers\Resource;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Resource\Publication;
use App\Services\Interfaces\ICategoryService;
use App\Services\Interfaces\IPublicationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * @var ICategoryService
     */
    private ICategoryService $categoryService;

    /**
     * @var ResourceController
     */
    private ResourceController|IPublicationService $publicationService;

    /**
     * CategoryController constructor.
     *
     * @param IpublicationService $publicationService
     */
    public function __construct(ICategoryService $category, IPublicationService $publicationService)
    {
        $this->publicationService = $publicationService;
        $this->categoryService = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = $this->publicationService->listPublications($request->all());
            return datatables()->of($items)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('category_name', fn($row) => $row->category->name)
                ->addColumn('client_type_name', fn($row) => $row->category->name)
                ->addColumn('property_name', fn($row) => $row->category->name)
                ->addColumn('property__type_name', fn($row) => $row->category->name)
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "resources"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('resource.resources.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        $resource = new Publication();
        $resource->is_active = 1;
        $categories = $this->categoryService->listActiveCategories();

        return view("resource.resources.edit", compact("resource", "categories"));
    }

    public function showAll(Request $request)
    {
        $resources = $this->publicationService->listPublications($request->all(), 'updated_at');

        if(isset($request->type) && $request->type == '')
        {
            $resources = $resources->where('type', '==', $request->type);
        }
        $resources = $resources->groupBy('category_id');
        $categories = $this->categoryService->listActiveCategories();
        return view('resource.resources.all', compact('resources', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'file' => 'required_if:old_file,null'
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $publication = $this->publicationService->findCPublicationById($request->input("id"));
            $results = $this->publicationService->updatePublication($data, $publication);
        }else{
            $results = $this->publicationService->createPublication($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', $results->message);

        return redirect()->route('resource.resources.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Factory|\Illuminate\Foundation\Application|object|View
     */
    public function show($id)
    {
        $resource = $this->publicationService->findCPublicationById($id);
        $categories = $this->categoryService->listActiveCategories();

        return view("resource.resources.edit", compact("resource", "categories"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $resource = $this->publicationService->findCPublicationById($id);
        $categories = $this->categoryService->listActiveCategories();

        return view("resource.resources.edit", compact("resource", "categories"));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $publication = $this->publicationService->findCPublicationById($id);
        $result = $this->publicationService->deletePublication($publication);

        return $this->responseJson($result);
    }
}
