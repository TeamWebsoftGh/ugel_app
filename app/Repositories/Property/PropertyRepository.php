<?php

namespace App\Repositories\Property;


use App\Models\Property\Property;
use App\Repositories\BaseRepository;
use App\Repositories\Property\Interfaces\IPropertyRepository;

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
     * @param array $filter
     * @param string $order
     * @param string $sort
     * @return
     */
    public function listProperties(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->model->query();

        // Apply filters using when() for cleaner querying
        $query->when(!empty($filter['filter_property_category']), function ($q) use ($filter) {
            $q->whereHas('propertyCategory', function ($query) use ($filter) {
                $query->where('id', $filter['filter_property_category']);
            });
        });

        $query->when(!empty($filter['filter_property_type']), function ($q) use ($filter) {
            $q->where('property_type_id', $filter['filter_property_type']);
        });

        $query->when(!empty($filter['filter_active']), function ($q) use ($filter) {
            $q->where('is_active', $filter['filter_active']);
        });

        $query->when(!empty($filter['filter_name']), function ($q) use ($filter) {
            $q->where('property_name', 'like', '%' . $filter['filter_name'] . '%');
        });

        $query->when(!empty($filter['filter_city']), function ($q) use ($filter) {
            $q->where('city_id', $filter['filter_city']);
        });

        $query->when(!empty($filter['filter_purpose']), function ($q) use ($filter) {
            $q->where('property_purpose_id', $filter['filter_purpose']);
        });

        $query->when(!empty($filter['filter_status']), function ($q) use ($filter) {
            $q->where('status', $filter['filter_status']);
        });

        // Filter properties that have associated Property Units
        $query->when(!empty($filter['filter_has_units']), function ($q) {
            $q->whereHas('propertyUnits');
        });

        // Order by and return result
        return $query->orderBy($order, $sort);
    }
}
