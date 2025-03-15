<?php

namespace App\Repositories;


use App\Models\Property\PropertyType;
use App\Repositories\Interfaces\IPropertyTypeRepository;
use Illuminate\Support\Collection;

class PropertyTypeRepository extends BaseRepository implements IPropertyTypeRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param PropertyType $propertyType
     */
    public function __construct(PropertyType $propertyType)
    {
        parent::__construct($propertyType);
        $this->model = $propertyType;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return PropertyType
     */
    public function findPropertyTypeById(int $id): PropertyType
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return PropertyType
     */
    public function createPropertyType(array $data) : PropertyType
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param PropertyType $propertyType
     * @return bool
     */
    public function updatePropertyType(array $data, PropertyType $propertyType) : bool
    {
        return $propertyType->update($data);
    }

    /**
     * @param PropertyType $propertyType
     * @return bool
     */
    public function deletePropertyType(PropertyType $propertyType) : bool
    {
        return $propertyType->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listPropertyTypes(array $filter = [], string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->model->query();
        if (!empty($filter['filter_property_category']))
        {
            $result = $result->where('property_category_id', $filter['filter_property_category']);
        }

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
