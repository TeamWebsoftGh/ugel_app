<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:send-bulk-sms')->everyMinute();
Schedule::command('app:send-quick-sms')->everyThirtySeconds()->withoutOverlapping();
//Schedule::command('app:send-quick-whatsapp')->everyFifteenSeconds()->withoutOverlapping();
Schedule::command('app:send-email')->everyThirtySeconds()->withoutOverlapping();
//Schedule::command('app:send-quick-voice')->everyFifteenSeconds()->withoutOverlapping();
