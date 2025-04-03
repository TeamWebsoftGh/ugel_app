<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource\Category;
use Illuminate\Support\Collection;

interface ICategoryRepository extends IBaseRepository
{
    public function listCategories(string $order = 'updated_at', string $sort = 'desc', $except = []) : Collection;

    public function deleteFile(array $file, $disk = null) : bool;

    public function findCategoryBySlug(string $slug) : Category;

}
