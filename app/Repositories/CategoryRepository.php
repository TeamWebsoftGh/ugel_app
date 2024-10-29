<?php

namespace App\Repositories;

use App\Models\Resource\Category;
use App\Repositories\Interfaces\ICategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    /**
     * CategoryRepository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);
        $this->model = $category;
    }

    /**
     * List all the categories
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the category
     *
     * @param array $params
     *
     * @return Category
     */
    public function createCategory(array $params) : Category
    {
        $category = new Category($params);

        if (isset($params['parent'])) {
            $parent = $this->findCategoryById($params['parent']);
            $category->parent()->associate($parent);
        }

        $category->save();

        return $category;
    }

    /**
     * Update the category
     *
     * @param array $params
     *
     * @param Category $category
     * @return Category
     */
    public function updateCategory(array $params, Category $category) : Category
    {
        if (isset($params['parent'])) {
            $parent = $this->findCategoryById($params['parent']);
            $category->parent()->associate($parent);
        }

        $category->update($params);
        return $category;
    }

    /**
     * @param int $id
     * @return Category
     * @throws ModelNotFoundException
     */
    public function findCategoryById(int $id) : Category
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Delete a category
     *
     * @param Category $category
     * @return bool
     * @throws Exception
     */
    public function deleteCategory(Category $category) : bool
    {
        return $category->delete();
    }

    /**
     * @param $file
     * @param null $disk
     * @return bool
     */
    public function deleteFile(array $file, $disk = null) : bool
    {
        return $this->update(['cover' => null], $file['category']);
    }

    /**
     * Return the category by using the slug as the parameter
     *
     * @param string $slug
     *
     * @return Category
     * @throws ModelNotFoundException
     */
    public function findCategoryBySlug(string $slug) : Category
    {
        return $this->findOneByOrFail(['slug' => $slug]);
    }

    /**
     * @return mixed
     */
    public function findParentCategory()
    {
        return $this->model->parent;
    }

    /**
     * @return mixed
     */
    public function findChildren()
    {
        return $this->model->children;
    }
}
