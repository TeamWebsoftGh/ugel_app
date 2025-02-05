<?php

namespace App\Services\Helpers;


use App\Models\Auth\User;
use App\Models\Delegate\Constituency;
use App\Models\Delegate\Delegate;
use App\Models\Delegate\ElectoralArea;
use App\Models\Delegate\PollingStation;
use App\Models\Election\Election;
use App\Models\Election\ParliamentaryCandidate;
use App\Models\Election\PoliticalParty;
use App\Models\Resource\Category;
use App\Models\Settings\Region;
use Illuminate\Support\Collection;

class DelegteHelper
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

        return Delegate::select('id', 'first_name', 'surname', 'title');
    }

    public static function getAllConstituencies()
    {
        return Constituency::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllElectoralAreas($constituency_id = 1)
    {
        return ElectoralArea::select('id', 'name')
            ->where(['is_active' => 1, 'constituency_id' => $constituency_id])->get();
    }

    public static function getAllPoliticalParties()
    {
        return PoliticalParty::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllPollingStations($electoral_id = null)
    {
        /**
         *
         *
         * @return Collection
         */
        return PollingStation::select('id', 'name', 'code')
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

        return Category::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllRegions($country_id = 1)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return Region::select('id', 'name')
            ->where(['is_active' => 1, 'country_id' => $country_id])->get();
    }

    public static function getAllElections($type = null)
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        if($type != null)
        {
            return Election::select('id', 'name')
                ->where(['is_active' => 1, 'type' => $type])->get();
        }

        return Election::select('id', 'name')
            ->where(['is_active' => 1])->get();
    }

    public static function getAllParliamentaryCandidates($type = "parliamentary")
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

         return ParliamentaryCandidate::select('id', 'first_name', 'last_name', 'image','political_party_id')
         ->where(['type' => $type])
         ->with(['political_party:id,code'])
         ->get();
    }


    public static function getAllUsers()
    {
        /**
         * Admission Type
         *
         * @return Collection
         */

        return User::select('id', 'first_name', 'last_name', 'other_names')
            ->where(['is_active' => 1])->get()->except([1]);
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


