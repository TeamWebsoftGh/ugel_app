<?php

namespace App\Services\Helpers;

use App\Models\Auth\User;
use App\Models\Billing\BookingPeriod;
use App\Models\Billing\PropertyUnitPrice;
use App\Models\Client\Client;
use App\Models\Client\ClientType;
use App\Models\Property\Property;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyPurpose;
use App\Models\Property\PropertyType;
use App\Models\Property\PropertyUnit;
use App\Models\Settings\City;
use App\Models\Settings\Country;
use App\Models\Settings\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PropertyHelper
{
    /**
     * Get a User by ID
     */
    public static function getById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get All Users with optional filtering
     */
    public static function getAll(?bool $exc_exit = null, ?int $company_id = null): Collection
    {
        return User::select('id', 'first_name', 'last_name', 'title')
            ->when(!is_owner(), fn($query) => $query->where('company_id', user()->company_id))
            ->get();
    }

    /**
     * Get All Customers
     */
    public static function getAllCustomers(): Collection
    {
        return Client::select('id', 'first_name', 'last_name', 'title', 'business_name', 'client_number')->get();
    }

    /**
     * Get All Active Property Types
     */
    public static function getAllPropertyTypes(): Collection
    {
        return PropertyType::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    public static function getAllHostels(): Collection
    {
        return PropertyUnit::whereHas('property.propertyType', function ($query) {
            $query->where('short_name', 'hostel'); // Assuming 'name' is the column indicating the type
        })
            ->where('is_active', 1)
            ->get();
    }


    public static function getAllBookingPeriods(): Collection
    {
        return BookingPeriod::where('short_name', 'hostel')
            ->where('is_active', 1)
            ->get(['id', 'name', 'type', 'booking_start_date', 'booking_end_date']);
    }

    public static function getAllProperties($propertyTypeId): Collection
    {
        return Property::select('id', 'property_name', 'property_type_id')
            ->where('is_active', 1)
            ->orderBy('property_name')
            ->get();
    }

    /**
     * Get All Active Property Categories
     */
    public static function getAllPropertyCategories(): Collection
    {
        return PropertyCategory::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get All Active Property Purposes
     */
    public static function getAllPropertyPurposes(): Collection
    {
        return PropertyPurpose::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get All Active Countries
     */
    public static function getAllCountries(): Collection
    {
        return Country::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get All Active Regions (Filtered by Country)
     */
    public static function getAllRegions(?int $country_id = null): Collection
    {
        return Region::select('id', 'name')
            ->where('is_active', 1)
            ->when($country_id, fn($query) => $query->where('country_id', $country_id))
            ->orderBy('name')
            ->get();
    }

    /**
     * Get All Active Cities (Filtered by Region)
     */
    public static function getAllCities(?int $region_id = null): Collection
    {
        return City::select('id', 'name')
            ->where('is_active', 1)
            ->when($region_id, fn($query) => $query->where('region_id', $region_id))
            ->orderBy('name')
            ->get();
    }

    /**
     * Get All Active Client Types
     */
    public static function getAllClientTypes(): Collection
    {
        return ClientType::select('id', 'name', 'category')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();
    }

    public static function getPropertyUnitPrice($propertyUnitId, $bookingPeriodId = null)
    {
        $cacheKey = "property_unit_price:{$propertyUnitId}:{$bookingPeriodId}";

        return Cache::remember($cacheKey, 3600, function () use ($propertyUnitId, $bookingPeriodId) {
            if ($bookingPeriodId) {
                $propertyUnitPrice = PropertyUnitPrice::where([
                    ['property_unit_id', '=', $propertyUnitId],
                    ['booking_period_id', '=', $bookingPeriodId],
                ])->value('price');

                if ($propertyUnitPrice !== null) {
                    return $propertyUnitPrice;
                }
            }
            return PropertyUnit::where('id', $propertyUnitId)->value('rent_amount');
        });
    }

    /**
     * Get All Active Client Types Filtered by Category
     */
    public static function getAllClientTypesByCategory(string $category = "individual"): Collection
    {
        return ClientType::select('id', 'name', 'category')
            ->where('is_active', 1)
            ->where('category', $category)
            ->orderBy('name')
            ->get();
    }
}
