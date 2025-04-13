<?php

namespace Database\Seeders;

use App\Models\CustomerService\MaintenanceRequest;
use App\Models\CustomerService\SupportTicket;
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
            'subject_type' => MaintenanceRequest::class,
            'approval_route' => "maintenance-requests.show",
            'company_id' => $company_id,
            ]);

        WorkflowType::create([
            'name' => 'Support Ticket',
            'code' => 'support-ticket',
            'subject_type' => SupportTicket::class,
            'approval_route' => "support-tickets.show",
            'company_id' => $company_id,
        ]);
    }
}
