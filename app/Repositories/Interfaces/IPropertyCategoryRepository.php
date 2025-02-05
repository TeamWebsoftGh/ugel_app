<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\PropertyCategory;
use Illuminate\Support\Collection;

interface IPropertyCategoryRepository extends IBaseRepository
{
    public function listPropertyCategories(array $filter = [], string $order = 'id', string $sort = 'desc'): Collection;

    public function createPropertyCategory(array $data) : PropertyCategory;

    public function findPropertyCategoryById(int $id) : PropertyCategory;

    public function updatePropertyCategory(array $data, PropertyCategory $propertyCategory) : bool;

    public function deletePropertyCategory(PropertyCategory $propertyCategory);

}
