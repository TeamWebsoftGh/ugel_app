<?php

namespace App\Repositories;


use App\Models\CustomerService\MaintenanceCategory;
use App\Repositories\Interfaces\IMaintenanceCategoryRepository;

class MaintenanceCategoryRepository extends BaseRepository implements IMaintenanceCategoryRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param MaintenanceCategory $maintenanceCategory
     */
    public function __construct(MaintenanceCategory $maintenanceCategory)
    {
        parent::__construct($maintenanceCategory);
        $this->model = $maintenanceCategory;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return MaintenanceCategory
     */
    public function findMaintenanceCategoryById(int $id): MaintenanceCategory
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return MaintenanceCategory
     */
    public function createMaintenanceCategory(array $data) : MaintenanceCategory
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param MaintenanceCategory $maintenanceCategory
     * @return bool
     */
    public function updateMaintenanceCategory(array $data, MaintenanceCategory $maintenanceCategory) : bool
    {
        return $maintenanceCategory->update($data);
    }

    /**
     * @param MaintenanceCategory $maintenanceCategory
     * @return bool
     */
    public function deleteMaintenanceCategory(MaintenanceCategory $maintenanceCategory) : bool
    {
        return $maintenanceCategory->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     */
    public function listPropertyCategories(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*'])
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

        return $result->orderBy($order, $sort);
    }

}
