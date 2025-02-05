<?php

namespace App\Http\Controllers\Resource;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Resource\Category;
use App\Traits\JsonResponseTrait;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use JsonResponseTrait;
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

    public function index()
    {
        $categories = $this->categoryService->listCategories('created_at', 'asc');
        $category = new Category();
        return view('resource.categories.create', compact('categories', 'category'));
    }

    public function edit(Request $request, $id)
    {
        $categories = $this->categoryService->listMainCategories();
        $category = $this->categoryService->findCategoryById($id);

        if ($request->ajax()){
            return view('resource.categories.edit', compact('categories', 'category'));
        }

        return view('resource.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->listCategories('created_at', 'asc', 1);
        $category = new Category();
        return view('resource.categories.create', compact('categories', 'category'));
    }

    public function Store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required',
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
}
