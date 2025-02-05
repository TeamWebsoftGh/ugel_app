<?php

namespace App\Console\Commands;

use App\Models\Memo\Announcement;
use App\Models\Memo\SmsAlert;
use App\Models\Settings\Configuration;
use App\Services\Interfaces\IDelegateService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendQuickVoice extends Command
{
    use SmsTrait;
    protected IDelegateService $delegateService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-quick-voice';

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
        $sms = SmsAlert::whereNull('schedule_time')->where('type', 'voice')
            ->where(function($query) {
                $query->whereNull('is_sent')
                    ->orWhere('is_sent', 0);
            })
            ->take(50)  // Limit the number of results to 50
            ->get();

        $this->info(count($sms)." Quick Voice.");

        foreach ($sms as $s)
        {
            if(isset($s->file))
            {
                try {
                    $total = settings("voice_main_balance");
                    if($total > 0)
                    {
                        $pn = format_ghana_phone_number($s->to, "0");
                        $s->is_sent = 1;
                        $res = $this->sendVoice($pn, $s->file);
                        $s->campaign_id = $res?->data->campaign_id??null;
                        $s->status = $res->status??"failed";
                        $s->save();
                        Configuration::updateOrCreate([
                            'option_key' => 'voice_main_balance'
                        ], ['option_value' =>  ($total-1)]);
                    }
                }catch (\Exception $ex){
                    log_error(format_exception($ex), $s, "send-quick-voice");
                }
            }
        }
        return '';
    }
}
