<?php

namespace App\Services\Helpers;


use App\Models\Employees\Employee;
use App\Models\Organization\Department;
use App\Models\Organization\Designation;
use App\Models\Organization\EmployeeCategory;
use App\Models\Organization\EmployeeType;
use App\Models\Organization\Branch;
use App\Models\Timesheet\OfficeShift;
use App\Models\Organization\Subsidiary;
use Illuminate\Support\Collection;

class EmployeeHelper
{
    public static function getById(int $id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Employee::find($id);
    }

    public static function getByStaffId(string $staffId)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Employee::where('staff_id', $staffId)->get()->first();
    }

    public static function getAll($exc_exit = null, $company_id = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return Employee::select('id', 'first_name', 'last_name', 'staff_id', 'title')->get();
    }

    public static function getAllSubsidiaries($company_id = null)
    {
        return Subsidiary::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllDepartments($company_id = null)
    {
        return Department::select('id', 'department_name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllLocations($company_id = null)
    {
        $company_id = $company_id??user()->company_id;
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Branch::select('id', 'branch_name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllCategories($company_id = null)
    {
        $company_id = $company_id??user()->employee->company_id;
        /**
         * Admission Type
         *
         * @return Collection
         */

        return EmployeeCategory::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllTypes($company_id = null)
    {
        $company_id = $company_id??user()->company_id;
        /**
         * Admission Type
         *
         * @return Collection
         */

        return EmployeeType::select('id', 'emp_type_name')
            ->where(['is_active' => 1])->get();
    }


    public static function getAllDesignations($company_id = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return Designation::select('id', 'designation_name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllOfficeShifts($company_id = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return OfficeShift::select('id', 'shift_name')
            ->where(['is_active' => 1])->get();
    }
}


