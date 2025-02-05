<?php

namespace App\Repositories;

use App\Models\ServiceType;
use App\Repositories\Interfaces\IServiceTypeRepository;
use Illuminate\Support\Collection;

class ServiceTypeRepository extends BaseRepository implements IServiceTypeRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param ServiceType $serviceType
     */
    public function __construct(ServiceType $serviceType)
    {
        parent::__construct($serviceType);
        $this->model = $serviceType;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return ServiceType
     */
    public function findServiceTypeById(int $id): ServiceType
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $params
     *
     * @return ServiceType
     */
    public function createServiceType(array $params) : ServiceType
    {
        return $this->create($params);
    }

    /**
     * @param array $data
     *
     * @param ServiceType $serviceType
     * @return bool
     */
    public function updateServiceType(array $data, ServiceType $serviceType) : bool
    {
        return $serviceType->update($data);
    }

    /**
     * @param ServiceType $serviceType
     * @return bool
     */
    public function deleteServiceType(ServiceType $serviceType) : bool
    {
        return $serviceType->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listServiceTypes(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }

}
