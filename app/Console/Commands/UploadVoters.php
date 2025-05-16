<?php

namespace App\Console\Commands;

use App\Imports\DelegateImport;
use App\Models\Communication\Announcement;
use App\Models\Communication\SmsAlert;
use App\Services\Interfaces\IDelegateService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class UploadVoters extends Command
{
    use SmsTrait;
    protected IDelegateService $delegateService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:voters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        Excel::queueImport(new DelegateImport(), public_path("uploads/voters.csv"));
        return '';
    }
}
