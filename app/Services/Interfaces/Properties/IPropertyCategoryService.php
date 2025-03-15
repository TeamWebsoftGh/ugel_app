<?php

namespace App\Services\Interfaces\Properties;

use App\Models\Property\PropertyCategory;
use App\Services\Interfaces\IBaseService;
use Illuminate\Support\Collection;

interface IPropertyCategoryService extends IBaseService
{
    public function listPropertyCategories(array $filter = [], string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createPropertyCategory(array $params);

    public function findPropertyCategoryById(int $id);

    public function updatePropertyCategory(array $params, PropertyCategory $propertyCategory);

    public function deletePropertyCategory(PropertyCategory $propertyCategory);


    public function deleteMultiplePropertyCategories(array $ids);
}
