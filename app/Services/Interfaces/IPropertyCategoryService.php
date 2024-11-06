<?php

namespace App\Services\Interfaces;

use App\Models\Property\PropertyCategory;
use Illuminate\Support\Collection;

interface IPropertyCategoryService extends IBaseService
{
    public function listPropertyCategories(array $filter = null, string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createPropertyCategory(array $params);

    public function findPropertyCategoryById(int $id);

    public function updatePropertyCategory(array $params, PropertyCategory $propertyCategory);

    public function deletePropertyCategory(PropertyCategory $propertyCategory);
}
