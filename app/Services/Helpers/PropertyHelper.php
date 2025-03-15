<?php

namespace App\Services\Helpers;

use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Client\ClientType;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyPurpose;
use App\Models\Property\PropertyType;
use App\Models\Settings\City;
use App\Models\Settings\Country;
use App\Models\Settings\Region;
use Illuminate\Support\Collection;

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
        return Client::select('id', 'first_name', 'last_name', 'title', 'business_name')->get();
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
