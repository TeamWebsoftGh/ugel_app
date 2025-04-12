<?php

namespace App\Console\Commands;

use App\Models\Communication\Announcement;
use App\Models\Communication\SmsAlert;
use App\Services\Interfaces\IDelegateService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendQuickSms extends Command
{
    use SmsTrait;
    protected IDelegateService $delegateService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-quick-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sms = SmsAlert::whereNull('schedule_time')->where('type', 'sms')
            ->where(function($query) {
                $query->whereNull('is_sent')
                    ->orWhere('is_sent', 0);
            })
            ->take(50)  // Limit the number of results to 50
            ->get();

        $this->info(count($sms)." Quick Sms.");

        foreach ($sms as $s)
        {
            $s->is_sent = 1;
            $res = $this->sendSms($s->to, $s->message);
            $s->status = $res?->status;


            if (isset($res?->data) && is_array($res->data)) {
                // Find the corresponding data entry for this SMS
                $sentData = collect($res->data)->first();

                if ($sentData && isset($sentData->id)) {
                    $s->campaign_id = $sentData->id; // Save the recipient ID
                } else {
                    // Handle cases where the recipient ID isn't found
                    $this->error("Recipient ID not found for number: {$s->to}");
                }
            }
            $s->save();
        }
        return '';
    }
}
