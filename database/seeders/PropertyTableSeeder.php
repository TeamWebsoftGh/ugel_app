<?php

namespace Database\Seeders;

use App\Models\Property\Amenity;
use App\Models\Property\PropertyCategory;
use App\Models\Property\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define categories and their associated types
        $categories = [
            [
                'name' => 'Residential',
                'description' => 'Properties for living and housing purposes',
                'types' => [
                    ['name' => 'Single-family home', 'description' => 'Standalone home for one family'],
                    ['name' => 'Apartment', 'description' => 'Unit in a multi-family building'],
                    ['name' => 'Villa', 'description' => 'Privately owned unit in a complex'],
                ]
            ],
            [
                'name' => 'Commercial',
                'description' => 'Properties for business or retail use',
                'types' => [
                    ['name' => 'Office building', 'description' => 'Space for offices'],
                    ['name' => 'Retail space', 'description' => 'Space for shops and retail stores'],
                ]
            ],
            [
                'name' => 'Industrial',
                'description' => 'Properties for manufacturing and storage purposes',
                'types' => [
                    ['name' => 'Factory', 'description' => 'Manufacturing facility'],
                    ['name' => 'Warehouse', 'description' => 'Storage facility'],
                ]
            ],
            [
                'name' => 'Land',
                'description' => 'Undeveloped or agricultural land',
                'types' => [
                    ['name' => 'Agricultural land', 'description' => 'Land for farming and agriculture'],
                    ['name' => 'Construction plot', 'description' => 'Land designated for construction'],
                ]
            ],
            [
                'name' => 'Mixed-use',
                'description' => 'Properties that combine residential and commercial purposes',
                'types' => [
                    ['name' => 'Retail-residential building', 'description' => 'Buildings combining commercial and residential units'],
                ]
            ],
            [
                'name' => 'Special-purpose',
                'description' => 'Properties for specific uses like schools, hospitals, and event spaces',
                'types' => [
                    ['name' => 'Hospital', 'description' => 'Healthcare facility'],
                    ['name' => 'School', 'description' => 'Educational institution'],
                ]
            ]
        ];

        // Loop through each category and its types
        foreach ($categories as $categoryData) {
            // Create or update the property category
            $category = PropertyCategory::updateOrCreate(
                ['name' => $categoryData['name']],
                ['short_name' => Str::slug($categoryData['name'])],
                ['description' => $categoryData['description']]
            );

            // Loop through each type under the category and create or update them
            foreach ($categoryData['types'] as $typeData) {
                PropertyType::updateOrCreate(
                    [
                        'name' => $typeData['name'], // Condition to check
                        'property_category_id' => $category->id // Ensure the type is linked to the right category
                    ],
                    [
                        'short_name' => Str::slug($typeData['name']),
                        'description' => $typeData['description'] // Fields to update or create
                    ]
                );
            }
        }

        //Add Amenities
        $amenities = [
            ['name' => 'Swimming Pool', 'description' => 'Olympic size pool'],
            ['name' => 'Gym', 'description' => 'Fully-equipped gym with cardio machines and weights'],
            ['name' => 'Air Conditioning', 'description' => 'Central air conditioning system'],
            ['name' => 'Parking', 'description' => 'Secure covered parking space'],
            ['name' => 'Laundry Room', 'description' => 'Shared laundry facilities on each floor'],
            ['name' => '24/7 Security', 'description' => 'Round-the-clock security services'],
            ['name' => 'Elevator', 'description' => 'High-speed elevators for all floors'],
            ['name' => 'Playground', 'description' => 'Children\'s playground with swings and slides'],
            ['name' => 'Wi-Fi', 'description' => 'High-speed internet access throughout the building'],
        ];

        foreach ($amenities as $amenityData) {
            Amenity::updateOrCreate(
                ['name' => $amenityData['name']],
                ['description' => $amenityData['description']]
            );
        }
    }
}
