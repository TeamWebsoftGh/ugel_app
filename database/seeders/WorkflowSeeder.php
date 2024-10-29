<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Workflow\WorkflowPositionType;
use App\Models\Workflow\WorkflowType;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company_id = 1;
        foreach (\App\Constants\Constants::POSITION_TYPES as $key => $type)
        {
            WorkflowPositionType::create([
                'name' => $type,
                'code' => $key,
                'company_id' => $company_id,
            ]);
        }

        WorkflowType::create([
            'name' => 'Application Request',
            'code' => 'application-request',
            'subject_type' => Offer::class,
            'company_id' => $company_id,
            ]);
    }
}
