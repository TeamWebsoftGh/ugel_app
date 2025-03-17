<?php

namespace App\Traits;

use App\Models\Auth\User;
use App\Models\Common\Priority;
use App\Models\Common\Status;
use App\Models\CustomerService\MaintenanceCategory;
use Illuminate\Support\Collection;

class TaskUtil
{
    public static function getById(int $id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return User::find($id);
    }

    public static function getByStaffId(string $staffId)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return User::where('staff_id', $staffId)->get()->first();
    }

    public static function getAll($exc_exit = null, $company_id = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        $employee = User::select('id', 'first_name', 'last_name', 'staff_id', 'title')
            ->where(['status' => 1])->get();

        return $employee;
    }

    public static function getPriorities()
    {
        /**
         * Priority
         *
         * @return Collection
         */

        return Priority::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllStatuses()
    {
        /**
         * Status
         *
         * @return Collection
         */

        return Status::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getMaintenanceCategories()
    {
        /**
         * Priority
         *
         * @return Collection
         */

        return MaintenanceCategory::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }


    public static function getAllUnits($department = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        $data = Unit::where(['status' => 1]);
        if($department != null)
        {
            $data = $data->where(['department_id' => $department]);
        }

        return $data->select('id', 'unit_name')->get();
    }
}
