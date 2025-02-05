<?php

namespace App\Repositories;


use App\Models\Property\PropertyCategory;
use App\Repositories\Interfaces\IPropertyCategoryRepository;
use Illuminate\Support\Collection;

class PropertyCategoryRepository extends BaseRepository implements IPropertyCategoryRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param PropertyCategory $propertyCategory
     */
    public function __construct(PropertyCategory $propertyCategory)
    {
        parent::__construct($propertyCategory);
        $this->model = $propertyCategory;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return PropertyCategory
     */
    public function findPropertyCategoryById(int $id): PropertyCategory
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return PropertyCategory
     */
    public function createPropertyCategory(array $data) : PropertyCategory
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param PropertyCategory $propertyCategory
     * @return bool
     */
    public function updatePropertyCategory(array $data, PropertyCategory $propertyCategory) : bool
    {
        return $propertyCategory->update($data);
    }

    /**
     * @param PropertyCategory $propertyCategory
     * @return bool
     */
    public function deletePropertyCategory(PropertyCategory $propertyCategory) : bool
    {
        return $propertyCategory->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listPropertyCategories(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->model->query();

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('is_active', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('name', 'like', '%'.$filter['filter_name'].'%');
        }

        return $result->orderBy($order, $sort)->get();
    }

}
