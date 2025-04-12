<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Traits\Permissions;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateRoles extends Command
{
    use Permissions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = [
            'developer' => [],
            'customer' => [],
            'admin' => [
                'audit' => 'r',
                'admin-dashboard' => 'r',
                'global-access' => 'c,r,u,d',
                'roles' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'bookings' => 'c,r,u,d',
                'booking-periods' => 'c,r,u,d',
                'general-settings' => 'c,r,u,d',
                'contacts' => 'c,r,u,d',
                'whatsapps' => 'c,r,u,d',
                'invoices' => 'c,r,u,d',
                'bulk-sms' => 'c,r,u,d',
                'companies' => 'c,r,u,d',
                'invoice-items' => 'c,r,u,d',
                'payments' => 'c,r,u,d',
                'customers' => 'c,r,u,d',
                'customer-types' => 'c,r,u,d',
                'payment-gateways' => 'c,r,u,d',
                'enquiries' => 'c,r,u,d',
                'maintenance-requests' => 'c,r,u,d',
                'support-tickets' => 'c,r,u,d',
                'mail-settings' => 'r,u',
                'sms-settings' => 'r,u',
                'property-reports' => 'r',
                'maintenance-categories' => 'c,r,u,d',
                'knowledge-bases' => 'c,r,u,d',
                'court-cases' => 'c,r,u,d',
                'court-hearings' => 'c,r,u,d',
                'resources' => 'c,r,u,d',
                'popups' => 'c,r,u,d',
                'amenities' => 'c,r,u,d',
                'property-categories' => 'c,r,u,d',
                'property-types' => 'c,r,u,d',
                'properties' => 'c,r,u,d',
                'property-units' => 'c,r,u,d',
                'rooms' => 'c,r,u,d',
                'reviews' => 'c,r,u,d',
                'categories' => 'c,r,u,d',
                'teams' => 'c,r,u,d',
                'position-types' => 'c,r,u,d',
                'workflows' => 'c,r,u,d',
                'workflow-positions' => 'c,r,u,d',
                'workflow-types' => 'c,r,u,d',
                'workflow-requests' => 'c,r,u,d',
            ],

            'it-support' => [
                'audit' => 'r',
                'admin-dashboard' => 'r',
                'global-access' => 'c,r,u,d',
                'roles' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'bookings' => 'c,r,u,d',
                'booking-periods' => 'c,r,u,d',
                'general-settings' => 'c,r,u,d',
                'contacts' => 'c,r,u,d',
                'whatsapps' => 'c,r,u,d',
                'invoices' => 'c,r,u,d',
                'bulk-sms' => 'c,r,u,d',
                'companies' => 'c,r,u,d',
                'invoice-items' => 'c,r,u,d',
                'payments' => 'c,r,u,d',
                'customers' => 'c,r,u,d',
                'customer-types' => 'c,r,u,d',
                'payment-gateways' => 'c,r,u,d',
                'enquiries' => 'c,r,u,d',
                'maintenance-requests' => 'c,r,u,d',
                'support-tickets' => 'c,r,u,d',
                'mail-settings' => 'r,u',
                'sms-settings' => 'r,u',
                'property-reports' => 'r',
                'maintenance-categories' => 'c,r,u,d',
                'knowledge-bases' => 'c,r,u,d',
                'court-cases' => 'c,r,u,d',
                'court-hearings' => 'c,r,u,d',
                'resources' => 'c,r,u,d',
                'popups' => 'c,r,u,d',
                'amenities' => 'c,r,u,d',
                'property-categories' => 'c,r,u,d',
                'property-types' => 'c,r,u,d',
                'properties' => 'c,r,u,d',
                'property-units' => 'c,r,u,d',
                'rooms' => 'c,r,u,d',
                'reviews' => 'c,r,u,d',
                'categories' => 'c,r,u,d',
                'teams' => 'c,r,u,d',
                'position-types' => 'c,r,u,d',
                'workflows' => 'c,r,u,d',
                'workflow-positions' => 'c,r,u,d',
                'workflow-types' => 'c,r,u,d',
                'workflow-requests' => 'c,r,u,d',
            ],

            'staff' => [
                'admin-dashboard' => 'r',
                'booking-periods' => 'r',
                'bookings' => 'c,r,u',
                'contacts' => 'c,r,u',
                'invoices' => 'r,u',
                'bulk-sms' => 'c,r,u',
                'invoice-items' => 'c,r,u',
                'payments' => 'c,r,u',
                'customers' => 'c,r,u',
                'customer-types' => 'c,r,u',
                'payment-gateways' => 'r',
                'enquiries' => 'c,r,u,d',
                'maintenance-requests' => 'c,r,u',
                'support-tickets' => 'c,r',
                'property-reports' => 'r',
                'maintenance-categories' => 'c,r,u',
                'knowledge-bases' => 'c,r,u',
                'court-cases' => 'c,r,u',
                'court-hearings' => 'c,r,u',
                'resources' => 'c,r,u',
                'popups' => 'c,r,u',
                'amenities' => 'c,r,u',
                'property-categories' => 'r,u',
                'property-types' => 'r,u',
                'properties' => 'r,u',
                'property-units' => 'r,u',
                'rooms' => 'r,u',
                'reviews' => 'r',
                'categories' => 'r',
                'teams' => 'r',
                'workflow-positions' => 'r',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
