<?php

namespace App\Repositories\Property;


use App\Models\Property\PropertyUnit;
use App\Repositories\BaseRepository;
use App\Repositories\Property\Interfaces\IPropertyUnitRepository;

class PropertyUnitRepository extends BaseRepository implements IPropertyUnitRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param PropertyUnit $propertyUnit
     */
    public function __construct(PropertyUnit $propertyUnit)
    {
        parent::__construct($propertyUnit);
        $this->model = $propertyUnit;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return PropertyUnit
     */
    public function findPropertyUnitById(int $id): PropertyUnit
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return PropertyUnit
     */
    public function createPropertyUnit(array $data) : PropertyUnit
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param PropertyUnit $propertyUnit
     * @return bool
     */
    public function updatePropertyUnit(array $data, PropertyUnit $propertyUnit) : bool
    {
        return $propertyUnit->update($data);
    }

    /**
     * @param PropertyUnit $propertyUnit
     * @return bool
     */
    public function deletePropertyUnit(PropertyUnit $propertyUnit) : bool
    {
        return $propertyUnit->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return
     */
    public function listPropertyUnits(array $filter = [], string $order = 'updated_at', string $sort = 'desc', array $columns = ['*'])
    {
        $result = $this->model->query();
        if (!empty($params['filter_property_type']))
        {
            $result = $result->whereHas('property', function ($query) use($params) {
                return $query->where('property_type_id', '=', $params['filter_property_type']);
            });
        }
        if (!empty($filter['filter_property']))
        {
            $result = $result->where('property_id', $filter['filter_property']);
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('status', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('unit_name', 'like', '%'.$filter['filter_name'].'%');
        }

        return $result->orderBy($order, $sort);
    }

}
