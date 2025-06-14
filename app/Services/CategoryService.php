<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Resource\Category;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryService extends ServiceBase implements ICategoryService
{
    use UploadableTrait;

    private ICategoryRepository $categoryRepo;

    /**
     * CategoryService constructor.
     *
     * @param ICategoryRepository $categoryRepository
     */
    public function __construct(ICategoryRepository $categoryRepository){
        parent::__construct();
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * List all the Categories
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $except
     * @return Collection
     */
    public function listCategories(string $order = 'updated_at', string $sort = 'desc', $except = []): Collection
    {
        return $this->categoryRepo->listCategories($order, $sort, $except);
    }

    /**
     * List active the Categories
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $except
     * @return Collection
     */
    public function listActiveCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->categoryRepo->listCategories($order, $sort, $except)->where('is_active', '==', 1);
    }

    /**
     * List all the Categories
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $except
     * @return Collection
     */
    public function listMainCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return $this->categoryRepo->listCategories($order, $sort, $except)->whereIn('parent_id', [1, 0])->where('is_active', 1);
    }

    /**
     * Create Category
     *
     * @param array $params
     *
     * @return Response
     */
    public function createCategory(array $params)
    {
        //Declaration
        $category = null;
        $cover = null;

        //Process Request
        try {
            if (isset($params['name'])) {
                $params['slug'] = Str::slug($params['name']).'-'.time();
            }
            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $filename = $params['slug'].'-'.time();
                $cover = $this->uploadPublic($params['cover'], 'categories', $filename);
            }
            $collection = collect($params);
            $merge = $collection->merge(compact('cover'));

            $category = $this->categoryRepo->create($merge->all());
        } catch (\Exception $e) {
            log_error(format_exception($e), new Category(), 'create-category-failed');
        }

        return $this->buildCreateResponse($category);
    }



    /**
     * Find the Category by id
     *
     * @param int $id
     *
     * @return Category
     */
    public function findCategoryById(int $id)
    {
        return $this->categoryRepo->findOneOrFail($id);
    }

    /**
     * Find the Category by Slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function findCategoryBySlug(string $slug)
    {
        return $this->categoryRepo->findCategoryBySlug($slug);
    }


    /**
     * Update Category
     *
     * @param array $params
     *
     * @param Category $category
     * @return Response
     */
    public function updateCategory(array $params, Category $category)
    {
        //Declaration
        $collection = collect($params);
        $slug = Str::slug($params['name']);
        $cover = "";
        $result = false;

        //Process Request
        try {
            //Check if Category has Image and Upload
            if (isset($params['cover']) && ($params['cover'] instanceof UploadedFile)) {
                $filename = $slug.'-'.time();
                $cover = $this->uploadPublic($params['cover'], 'categories', $filename);
            }

            $merge = $collection->merge(compact('slug', 'cover'));

            $result = $this->categoryRepo->update($merge->all(), $category->id);
        } catch (\Exception $e) {
            log_error(format_exception($e), $category, 'update-category-failed');
        }

        return $this->buildUpdateResponse($category, $result);
    }

    /**
     * @param array $file
     * @param null $disk
     * @return bool
     */
    public function deleteFile(array $file, $disk = null)
    {
        return $this->categoryRepo->deleteFile($file);
    }


    /**
     * @param Category $category
     * @return Response
     */
    public function deleteCategory(Category $category)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->categoryRepo->delete($category->id);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $category, 'create-category-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-category-successful';
        $auditMessage = 'You have successfully deleted a category with name '.$category->name;

        log_activity($auditMessage, $category, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function deleteMultipleCategories(array $ids)
    {
        //Declaration
        $result = $this->categoryRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
