<?php

namespace Database\Seeders;

use App\Models\CustomerService\Maintenance;
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
            'name' => 'Maintenance Request',
            'code' => 'maintenance-request',
            'subject_type' => Maintenance::class,
            'company_id' => $company_id,
            ]);
    }
}
