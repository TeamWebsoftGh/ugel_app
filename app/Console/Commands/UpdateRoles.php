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
    protected $description = 'Send Birthday Message to Employees';

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
            'admin' => [
                'audit' => 'r',
                'admin-dashboard' => 'r',
                'global-access' => 'c,r,u,d',
                'roles' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'agents' => 'c,r,u,d',
                'general-settings' => 'c,r,u,d',
                'contacts' => 'c,r,u,d',
                'whatsapps' => 'c,r,u,d',
                'bulk-voices' => 'c,r,u,d',
                'bulk-sms' => 'c,r,u,d',
                'companies' => 'c,r,u,d',
                'constituencies' => 'c,r,u,d',
                'polling-stations' => 'c,r,u,d',
                'electoral-areas' => 'c,r,u,d',
                'voters' => 'c,r,u,d',
                'elections' => 'c,r,u,d',
                'political-parties' => 'c,r,u,d',
                'parliamentary-candidates' => 'c,r,u,d',
                'election-results' => 'c,r,u,d',
                'mail-settings' => 'r,u',
                'sms-settings' => 'r,u',
                'voter-reports' => 'r',
                'knowledge-bases' => 'c,r,u,d',
                'resources' => 'c,r,u,d',
            ],

            'agent' => [
                'admin-dashboard' => 'r',
                'agents' => 'r,u',
                'voters' => 'c,r,u,d',
                'constituencies' => 'r',
                'polling-stations' => 'r',
                'electoral-areas' => 'r',
                'voter-reports' => 'r',
                'knowledge-bases' => 'r',
                'resources' => 'r',
            ],

            'it-support' => [
                'admin-dashboard' => 'r',
                'agents' => 'r,u',
                'voters' => 'c,r,u,d',
                'constituencies' => 'r',
                'polling-stations' => 'r',
                'electoral-areas' => 'r',
                'voter-reports' => 'r',
                'knowledge-bases' => 'r',
                'bulk-sms' => 'r',
                'resources' => 'r',
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
