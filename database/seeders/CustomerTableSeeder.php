<?php

namespace Database\Seeders;

use App\Models\Client\Client;
use App\Models\Client\ClientType;
use App\Models\Organization\Company;
use App\Models\Property\Amenity;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company_id = Company::all()->first()?->id;
        //Add Amenities
        $types = [
            ['name' => 'Students', 'code' => 'student', 'category' => 'individual'],
            ['name' => 'Individual', 'code' => 'individual', 'category' => 'individual'],
            ['name' => 'Business', 'code' => 'business', 'category' => 'business'],
        ];

        foreach ($types as $type) {
            ClientType::updateOrCreate(
                ['name' => $type['name'],'company_id' => $company_id],
                ['code' => $type['code'], 'category' => $type['category']]
            );
        }
    }
}
