<?php

namespace App\Repositories;

use App\Models\Employees\Employee;
use App\Repositories\Interfaces\IEmployeeRepository;
use Illuminate\Support\Collection;

class EmployeeRepository extends BaseRepository implements IEmployeeRepository
{
    /**
     * EmployeeRepository constructor.
     *
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        parent::__construct($employee);
        $this->model = $employee;
    }

    /**
     * List all the Employees
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $employees
     */
    public function listEmployees(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = Employee::query()->where('exit_date', null);

        if (!empty($params['filter_department']))
        {
            $result = $result->where('department_id', $params['filter_department']);
        }

        if (!empty($params['filter_subsidiary']))
        {
            $result = $result->where('subsidiary_id', $params['filter_subsidiary']);
        }

        if (!empty($params['filter_designation']))
        {
            $result = $result->where('designation_id', $params['filter_designation']);
        }

        if (!empty($params['filter_branch']))
        {
            $result = $result->where('branch_id', $params['filter_branch']);
        }

        if (!empty($params['filter_office_shift']))
        {
            $result = $result->where('office_shift_id', $params['filter_office_shift']);
        }

        if (!empty($params['filter_status']))
        {
            $result = $result->where('status_id', $params['filter_status']);
        }

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * List all the Employees
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $employees
     */
    public function listExitedEmployees(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = Employee::withoutGlobalScope('exit_date')->whereNotNull('exit_date');

        if (!empty($params['filter_department']))
        {
            $result = $result->where('department_id', $params['filter_department']);
        }

        if (!empty($params['filter_subsidiary']))
        {
            $result = $result->where('subsidiary_id', $params['filter_subsidiary']);
        }

        if (!empty($params['filter_designation']))
        {
            $result = $result->where('designation_id', $params['filter_designation']);
        }

        if (!empty($params['filter_branch']))
        {
            $result = $result->where('branch_id', $params['filter_branch']);
        }

        if (!empty($params['filter_office_shift']))
        {
            $result = $result->where('office_shift_id', $params['filter_office_shift']);
        }

        if (!empty($params['filter_status']))
        {
            $result = $result->where('status_id', $params['filter_status']);
        }

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the Employee
     *
     * @param array $data
     *
     * @return Employee
     */
    public function createEmployee(array $data): Employee
    {
        return $this->create($data);
    }

    /**
     * Find the Employee by id
     *
     * @param int $id
     *
     * @return Employee
     */
    public function findEmployeeById(int $id): Employee
    {
        return $this->findOneOrFail($id);
    }

     /**
     * Find the Employee by id
     *
     * @param int $id
     *
     * @return Employee
     */
    public function findEmployeeByStaffId(string $staff_id): Employee
    {
        return $this->model->where('staff_id',$staff_id)->first();
    }

     /**
     * Find the Employee by id
     *
     * @param int $id
     *
     * @return optional
     */
    public function findEmployeeUsersByStaffId(string $staff_id)
    {
        return optional($this->model->where('staff_id',$staff_id)
        ->join('users','users.email','employees.email')
        ->selectRaw('employees.*, users.id as employee_user_id')
        ->first());
    }


      /**
     * Find the Employee by staff_id
     *
     * @param int $staff_id
     *
     * @return object
     */
    public function findEmployeeByCriteria(array $criteria) : Employee
    {
        $data = Employee::where($criteria)
        ->join('users','users.email','employees.email')
        ->first();

        return $data;
    }

    /**
     * Update Employee
     *
     * @param array $params
     *
     * @param Employee $employee
     * @return bool
     */
    public function updateEmployee(array $params, Employee $employee): bool
    {
        return $employee->update($params);
    }

    /**
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listAllEmployees(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->all();
    }

}
