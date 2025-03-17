<?php

namespace Database\Seeders;

use App\Models\CustomerService\MaintenanceCategory;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // **Create Parent Categories**
        $carpentry = MaintenanceCategory::updateOrCreate(
            ['name' => 'Carpentry Issue'],
            ['short_name' => 'CARP']
        );

        $plumbing = MaintenanceCategory::updateOrCreate(
            ['name' => 'Plumbing Issue'],
            ['short_name' => 'PLUMB']
        );

        $electrical = MaintenanceCategory::updateOrCreate(
            ['name' => 'Electrical Issue'],
            ['short_name' => 'ELEC']
        );

        // **Carpentry Subcategories**
        $carpentrySubcategories = [
            'Main Door',
            'Main Door Lock',
            'Main Door Handle',
            'Wardrobe Lock',
            'Wardrobe Handle(s)',
            'Wardrobe Hinge(s)',
            'Wardrobe Drawer',
            'Bed Slab',
            'Bed Side(s)',
            'Louvre Blade(s)',
            'Torn Window Nettings',
            'Washroom Door Lock',
            'Pipe Duct Covering',
            'Balcony Door Lock',
            'Other (Specify)'
        ];

        // **Plumbing Subcategories**
        $plumbingSubcategories = [
            'Basin Tap Faulty',
            'Faulty WC',
            'WC Cistern Faulty',
            'WC Clogged',
            'WC Leakage',
            'WC Outlet Tube Removed',
            'Shower Broken',
            'Stop Cork Faulty',
            'Faulty Tap',
            'Bathroom Algae',
            'Balcony Algae',
            'Bedroom Algae',
            'Bathroom Sink Clogged',
            'Bathroom Sink Leakage',
            'Bathroom Drain Clogged',
            'Shortage Of Water',
            'Bathroom Ceiling Leakage',
            'Pipe Duct Leakage',
            'Cracked Tiles',
            'Bulged Tiles',
            'Other (Specify)'
        ];

        // **Electrical Subcategories**
        $electricalSubcategories = [
            'No Power',
            'Slow Fan',
            'Noisy Fan',
            'Faulty Fan',
            'Faulty Fan Regulator',
            'Dead Room Bulb(s)',
            'Faulty Switch',
            'Faulty Double Socket(s)',
            'Faulty Balcony Socket',
            'Dead Bathroom Bulb(s)',
            'Faulty Bathroom Switch',
            'Dead Balcony Bulb',
            'Faulty Balcony Switch',
            'Other (Specify)'
        ];

        // Insert Carpentry Subcategories
        foreach ($carpentrySubcategories as $subcategory) {
            MaintenanceCategory::updateOrCreate(
                ['name' => $subcategory, 'parent_id' => $carpentry->id],
                ['short_name' => strtoupper(str_replace(' ', '_', $subcategory))]
            );
        }

        // Insert Plumbing Subcategories
        foreach ($plumbingSubcategories as $subcategory) {
            MaintenanceCategory::updateOrCreate(
                ['name' => $subcategory, 'parent_id' => $plumbing->id],
                ['short_name' => strtoupper(str_replace(' ', '_', $subcategory))]
            );
        }

        // Insert Electrical Subcategories
        foreach ($electricalSubcategories as $subcategory) {
            MaintenanceCategory::updateOrCreate(
                ['name' => $subcategory, 'parent_id' => $electrical->id],
                ['short_name' => strtoupper(str_replace(' ', '_', $subcategory))]
            );
        }
    }
}
