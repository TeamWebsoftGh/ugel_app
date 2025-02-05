<?php

namespace App\Helpers;

use App\Models\Employee;
use App\Models\Employees\LeaveType;

class LeaveTypeHelper {

    public static function getLeaveTypes(Employee $employee = null)
    {
        $types = LeaveType::where('is_active', 1);

        if($employee != null){
            $types = $types->where('gender', strtolower($employee->gender))->orWhere("gender", null)->orWhere('gender', '');
        }

        return $types->get();
    }

    public static function getEducationalLevels()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return EducationalLevel::where('status', 1)->get();
    }

    public static function getCareerLevels()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return CareerLevel::where('status', 1)->get();
    }


    public static function getSkills()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Skill::where('status', 1)->get();
    }

    public static function getCountries()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Country::where('status', 0)->get();
    }
    public static function getRegions($id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Province::where('country_id', $id)->get();
    }


    public static function getCities($id)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return City::where('province_id', $id)->get();
    }

    public static function getJobTitle()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return JobTitle::where('status', 1)->get();
    }

    public static function getJobType()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return JobType::where('status', 1)->get();
    }

    public static function getCategories()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Category::where('status', 1)->get();
    }

    public static function getCompanies()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Company::where('status', 1)->get();
    }

    public static function getIndustryTypes()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return IndustryType::where('status', 1)->get();
    }

    public static function getCompanySizes()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return CompanySize::where('status', 1)->get();
    }

    public static function getRoles()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */
        return Role::all()->skip(1);
    }

}

