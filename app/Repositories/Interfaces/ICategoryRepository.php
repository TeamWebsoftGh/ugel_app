<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource\Category;
use Illuminate\Support\Collection;

interface ICategoryRepository extends IBaseRepository
{
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createCategory(array $params) : Category;

    public function updateCategory(array $params, Category $category) : Category;

    public function findCategoryById(int $id) : Category;

    public function deleteCategory(Category $category) : bool;

    public function deleteFile(array $file, $disk = null) : bool;

    public function findCategoryBySlug(string $slug) : Category;

}
