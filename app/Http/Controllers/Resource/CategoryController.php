<?php

namespace App\Http\Controllers\Resource;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Resource\Category;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class   CategoryController extends Controller
{
    /**
     * @var ICategoryService
     */
    private ICategoryService $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param ICategoryService $categoryService
     */
    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $amenities = $this->categoryService->listCategories();
            return datatables()->of($amenities)
                ->setRowId(fn($row) => $row->id)
                ->addColumn('parent_name', fn($row) => $row->parent->name ?? 'Main Category')
                ->addColumn('status', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "booking-periods"))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('resource.categories.index');
    }

    public function edit(Request $request, $id)
    {
        $categories = $this->categoryService->listMainCategories();
        $category = $this->categoryService->findCategoryById($id);

        return request()->ajax()
            ? view('resource.categories.edit', compact('category', 'categories'))
            : redirect()->route('resource.categories.index');
    }

    public function create()
    {
        $categories = $this->categoryService->listCategories('created_at', 'asc', 1);
        $category = new Category();
        $category->is_active = 1;

        return request()->ajax()
            ? view('resource.categories.edit', compact('category', 'categories'))
            : redirect()->route('resource.categories.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $category = $this->categoryService->findCategoryById($request->input("id"));
            $results = $this->categoryService->updateCategory($data, $category);
        }else{
            $results = $this->categoryService->createCategory($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('resource.categories.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $writer = $this->categoryService->findCategoryById($id);
        $result = $this->categoryService->deleteCategory($writer);

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
        $result = $this->categoryService->deleteMultipleCategories($request->ids);
        return $this->responseJson($result);
    }
}
