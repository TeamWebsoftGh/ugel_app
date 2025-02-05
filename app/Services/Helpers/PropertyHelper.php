<?php

namespace App\Services\Helpers;


use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Client\ClientType;
use App\Models\Organization\Department;
use App\Models\Organization\Designation;
use App\Models\Organization\EmployeeCategory;
use App\Models\Organization\EmployeeType;
use App\Models\Organization\Branch;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyPurpose;
use App\Models\Property\PropertyType;
use App\Models\ServiceType;
use App\Models\Timesheet\OfficeShift;
use App\Models\Organization\Subsidiary;
use Illuminate\Support\Collection;

class PropertyHelper
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


    public static function getAll($exc_exit = null, $company_id = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        if(is_owner())
        {
            return User::select('id', 'first_name', 'last_name', 'title')->get();
        }

        return User::where('company_id', user()->company_id)->select('id', 'first_name', 'last_name', 'title')->get();

    }

    public static function getAllCustomers()
    {
        return Client::select('id', 'first_name', 'last_name', 'title','business_name')->get();
    }

    public static function getAllPropertyTypes($provider = null)
    {
        return PropertyType::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllPropertyCategories()
    {
        return PropertyCategory::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }


    public static function getAllPropertyPurposes()
    {
        return PropertyPurpose::select('id', 'name')
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

    public static function getAllClientTypes($category = null)
    {
        /**
         *
         *
         * @return Collection
         */

        return ClientType::select('id', 'name', 'category')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllClientTypesByCategory($category = "individual")
    {
        /**
         *
         *
         * @return Collection
         */

        return ClientType::select('id', 'name', 'category')
            ->where(['is_active' => 1, 'category' => $category])->get();
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


