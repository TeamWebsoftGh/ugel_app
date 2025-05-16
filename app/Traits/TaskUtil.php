<?php

namespace App\Traits;

use App\Models\Auth\User;
use App\Models\Common\Priority;
use App\Models\Common\Status;
use App\Models\CustomerService\MaintenanceCategory;
use App\Models\CustomerService\SupportTopic;
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
            ->where(['is_active' => 1])->whereNull('parent_id')->get();
    }

    public static function getSubMaintenanceCategories()
    {
        return MaintenanceCategory::select(
            'maintenance_categories.id',
            'maintenance_categories.name',
            'parent.name as parent_name',
            'parent.id as parent_id'
        )
            ->leftJoin('maintenance_categories as parent', 'maintenance_categories.parent_id', '=', 'parent.id')
            ->where('maintenance_categories.is_active', 1)
            ->whereNotNull('maintenance_categories.parent_id')
            ->get();
    }



    public static function getAllSupportTopics()
    {
        /**
         * Support Topic
         *
         * @return Collection
         */

        $data = SupportTopic::where(['is_active' => 1]);

        return $data->select('id', 'name')->get();
    }
}
