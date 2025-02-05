<?php

namespace App\Repositories;


use App\Models\Property\Property;
use App\Repositories\Interfaces\IPropertyRepository;
use Illuminate\Support\Collection;

class PropertyRepository extends BaseRepository implements IPropertyRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param Property $property
     */
    public function __construct(Property $property)
    {
        parent::__construct($property);
        $this->model = $property;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return Property
     */
    public function findPropertyById(int $id): Property
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Property
     */
    public function createProperty(array $data) : Property
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Property $property
     * @return bool
     */
    public function updateProperty(array $data, Property $property) : bool
    {
        return $this->update($data, $property->id);
    }

    /**
     * @param Property $property
     * @return bool
     */
    public function deleteProperty(Property $property) : bool
    {
        return $this->delete($property->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listProperties(array $filter = [], string $order = 'updated', string $sort = 'desc', array $columns = ['*'])
    {
        $result = $this->model->query();
        if (!empty($filter['filter_property_category']))
        {
            $result = $result->where('property_category_id', $filter['filter_property_category']);
        }

        if (!empty($filter['filter_property_type']))
        {
            $result = $result->where('property_type_id', $filter['filter_property_type']);
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('is_active', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('name', 'like', '%'.$filter['filter_name'].'%');
        }

        return $result->orderBy($order, $sort);
    }

}
