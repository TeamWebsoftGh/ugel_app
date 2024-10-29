<?php

namespace App\Repositories;

use App\Models\Organization\Department;
use App\Repositories\Interfaces\IDepartmentRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DepartmentRepository extends BaseRepository implements IDepartmentRepository
{
    /**
     * DepartmentRepository constructor.
     *
     * @param Department $department
     */
    public function __construct(Department $department)
    {
        parent::__construct($department);
        $this->model = $department;
    }

    /**
     * List all the Departments
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $departments
     */
    public function listDepartments(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Department
     *
     * @param array $data
     *
     * @return Department
     */
    public function createDepartment(array $data): Department
    {
        return $this->create($data);
    }

    /**
     * Find the Department by id
     *
     * @param int $id
     *
     * @return Department
     */
    public function findDepartmentById(int $id): Department
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Department
     *
     * @param array $params
     *
     * @param Department $department
     * @return bool
     */
    public function updateDepartment(array $params, Department $department): bool
    {
        return $department->update($params);
    }

    /**
     * @param Department $department
     * @return bool|null
     * @throws \Exception
     */
    public function deleteDepartment(Department $department)
    {
        return $department->delete();
    }
}
