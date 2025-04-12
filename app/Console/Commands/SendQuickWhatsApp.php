<?php

namespace App\Console\Commands;

use App\Models\Communication\Announcement;
use App\Models\Communication\SmsAlert;
use App\Services\Interfaces\IDelegateService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendQuickWhatsApp extends Command
{
    use SmsTrait;
    protected IDelegateService $delegateService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-quick-whatsapp';

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
        $sms = SmsAlert::whereNull('schedule_time')->where('type', 'whatsapp')
            ->where(function($query) {
                $query->whereNull('is_sent')
                    ->orWhere('is_sent', 0);
            })
            ->take(50)  // Limit the number of results to 50
            ->get();

        $this->info(count($sms)." Quick Whatsapp.");

        foreach ($sms as $s)
        {
            try {
                $pn = format_ghana_phone_number($s->to);
                $s->is_sent = 1;

                if(!empty($s->tem_type) && $s->tem_type != "custom")
                {
                    $res = $this->sendWhatAppMessages($pn, $s->tem_type, "template", $s->file_type, $s->media_id);
                }

                if(!empty($s->message))
                {
                    $res = $this->sendWhatAppMessages($pn, $s->message);
                }

                if(!empty($s->media_id))
                {
                    $this->info($s->file_type." Quick Whatsapp.");
                    $res = $this->sendWhatAppMedia($pn, $s->file_type, $s->media_id);
                }

                $s->campaign_id = $res?->messages[0]?->id??null;
                $s->status = isset($s->campaign_id)?"success":"failed";
                $s->save();
            }catch (\Exception $ex){
                log_error(format_exception($ex), $s, "send-quick-whatsapp");
            }
        }
        return '';
    }
}
