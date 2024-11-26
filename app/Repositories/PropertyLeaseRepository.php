<?php

namespace App\Repositories;


use App\Models\Property\PropertyDetail;
use App\Repositories\Interfaces\IPropertyLeaseRepository;
use Illuminate\Support\Collection;

class PropertyLeaseRepository extends BaseRepository implements IPropertyLeaseRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param PropertyLease $propertyLease
     */
    public function __construct(PropertyLease $propertyLease)
    {
        parent::__construct($propertyLease);
        $this->model = $propertyLease;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return PropertyLease
     */
    public function findPropertyLeaseById(int $id): PropertyLease
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return PropertyLease
     */
    public function createPropertyLease(array $data) : PropertyLease
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param PropertyLease $propertyLease
     * @return bool
     */
    public function updatePropertyLease(array $data, PropertyLease $propertyLease) : bool
    {
        return $propertyLease->update($data);
    }

    /**
     * @param PropertyLease $propertyLease
     * @return bool
     */
    public function deletePropertyLease(PropertyLease $propertyLease) : bool
    {
        return $propertyLease->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listPropertyLeases(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
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
