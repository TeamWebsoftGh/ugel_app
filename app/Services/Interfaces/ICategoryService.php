<?php

namespace App\Services\Interfaces;

use App\Models\Resource\Category;
use Illuminate\Support\Collection;

interface ICategoryService extends IBaseService
{
    public function listCategories(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function listActiveCategories(string $order = 'id', string $sort = 'desc', $except = []): Collection;

    public function createCategory(array $params);

    public function updateCategory(array $params, Category $category);

    public function findCategoryById(int $id);

    public function deleteCategory(Category $category);

    public function deleteFile(array $file, $disk = null);

    public function findCategoryBySlug(string $slug);

}
