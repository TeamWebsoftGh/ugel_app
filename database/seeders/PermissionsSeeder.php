<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Traits\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    use Permissions;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            'developer' => [],
//            'developer' => [
//                'permissions' => 'c,r,u,d',
//                'error-log' => 'r',
//                'audit' => 'r',
//                'admin-dashboard' => 'r',
//                'roles' => 'c,r,u,d',
//                'users' => 'c,r,u,d',
//                'employees' => 'c,r,u,d',
//                'general-settings' => 'c,r,u,d',
//                'companies' => 'c,r,u,d',
//                'departments' => 'c,r,u,d',
//                'subsidiaries' => 'c,r,u,d',
//                'locations' => 'c,r,u,d',
//                'designations' => 'c,r,u,d',
//                'announcements' => 'c,r,u,d',
//                'company-policies' => 'c,r,u,d',
//                'payroll-settings' => 'c,r,u,d',
//                'workflow-settings' => 'c,r,u,d',
//                'employee-settings' => 'c,r,u,d',
//                'mail-settings' => 'r,u',
//                'leave-settings' => 'c,r,u,d',
//                'events' => 'c,r,u,d',
//                'service-types' => 'c,r,u,d',
//                'employee-awards' => 'c,r,u,d',
//                'employee-travels' => 'c,r,u,d',
//                'employee-transfers' => 'c,r,u,d',
//                'employee-complaints' => 'c,r,u,d',
//                'employee-sanction' => 'c,r,u,d',
//                'employee-exit-management' => 'c,r,u,d',
//                'support-tickets' => 'c,r,u,d',
//                'employee-promotions' => 'c,r,u,d',
//                'employee-payslip' => 'c,r,u,d',
//                'employee-leaves' => 'c,r,u,d',
//                'past-employees' => 'r',
//                'holidays' => 'c,r,u,d',
//                'office-shifts' => 'c,r,u,d',
//                'employee-trainings' => 'c,r,u,d',
//                'offers' => 'c,r,u,d',
//                'file-manager' => 'c,r,u,d',
//                'employee-reports' => 'r',
//                'payroll-reports' => 'r',
//                'performance-contract-reports' => 'r',
//                'workflows' => 'c,r,u,d',
//                'workflow-positions' => 'c,r,u,d',
//                'workflow-types' => 'r,u',
//                'workflow-position-types' => 'c,r,u,d',
//                'access-workflow' => 'r',
//                'access-workflow-requests' => 'r',
//            ],
            'admin' => [
                'audit' => 'r',
                'admin-dashboard' => 'r',
                'roles' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'general-settings' => 'c,r,u,d',
                'companies' => 'c,r,u,d',
                'announcements' => 'c,r,u,d',
                'property-types' => 'c,r,u,d',
                'contact-groups' => 'c,r,u,d',
                'contacts' => 'c,r,u,d',
                'sms-templates' => 'c,r,u,d',
                'workflow-settings' => 'c,r,u,d',
                'mail-settings' => 'r,u',
                'support-tickets' => 'c,r,u,d',
                'workflows' => 'c,r,u,d',
                'workflow-positions' => 'c,r,u,d',
                'workflow-types' => 'r,u',
                'workflow-position-types' => 'c,r,u,d',
                'workflow-requests' => 'r',
                'knowledge-bases' => 'c,r,u,d',
            ],

            'institution-admin' => [
                'users' => 'c,r,u,d',
                'support-tickets' => 'c,r,u,d',
                'offers' => 'c,r,u,d',
            ],

            'it-support' => [
                'audit' => 'r',
                'admin-dashboard' => 'r',
                'roles' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'contact-groups' => 'c,r,u,d',
                'contacts' => 'c,r,u,d',
                'sms-templates' => 'c,r,u,d',
                'general-settings' => 'c,r,u,d',
                'companies' => 'c,r,u,d',
                'announcements' => 'c,r,u,d',
                'workflow-settings' => 'c,r,u,d',
                'mail-settings' => 'r,u',
                'support-tickets' => 'c,r,u,d',
                'workflows' => 'c,r,u,d',
                'workflow-positions' => 'c,r,u,d',
                'workflow-types' => 'r,u',
                'workflow-position-types' => 'c,r,u,d',
                'workflow-requests' => 'r',
                'knowledge-bases' => 'c,r,u,d',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
