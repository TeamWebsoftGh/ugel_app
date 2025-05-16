<?php

namespace App\Console\Commands;

use App\Mail\AlertMail;
use App\Models\Common\Email;
use Illuminate\Console\Command;

class SendEmailAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Request Alert';

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
        $alerts = Email::where('is_sent', 0)->get();
        if($alerts->count())
        {
            foreach ($alerts as $alert)
            {
                $alert->no_of_tries += 1;
                try {
                    if($alert->mailable)
                    {
                        send_mail($alert->mailable, $alert->emailable, $alert->to);
                        $alert->is_sent = 1;
                    }else{
                        send_mail(AlertMail::class, $alert, $alert->to);
                        $alert->is_sent = 1;
                    }
                }catch (\Exception $ex){
                    log_error(format_exception($ex), $alert, 'send-email-failed');
                    $this->info('Error Occurred.');
                    return false;
                }

                $alert->save();
            }
        }else{
            $this->info('No Alert.');
            return '';
        }

        $this->info('Successfully sent.');
        return '';
    }
}
