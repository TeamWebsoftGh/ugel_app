<?php

namespace App\Console\Commands;

use App\Models\Communication\Announcement;
use App\Models\Communication\SmsAlert;
use App\Services\Auth\Interfaces\IUserService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBulkSms extends Command
{
    use SmsTrait;

    protected IUserService $delegateService;

    protected $signature = 'app:send-bulk-sms';
    protected $description = 'Send bulk SMS for announcements';

    public function __construct(IUserService $delegateService)
    {
        parent::__construct();
        $this->delegateService = $delegateService;
    }

    public function handle()
    {
        $media_id = null;
        $announcements = Announcement::whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->where(function($query) {
                $query->whereNull('is_sent')
                    ->orWhere('is_sent', 0);
            })
            ->get(); // Consider chunking or pagination if a million rows

        $this->info(count($announcements) . " Bulk SMS announcements.");

        foreach ($announcements as $announcement) {
            $announcement->is_sent = 1;
            $announcement->save();

            try {
                $filter = [
                    'filter_constituency' => $announcement->constituency_id,
                    'filter_electoral_area' => $announcement->electoral_area_id,
                    'filter_polling_station' => $announcement->polling_station_id,
                    'filter_region' => $announcement->region_id,
                    'filter_gender' => $announcement->gender,
                    'filter_min_age' => $announcement->min_age,
                    'filter_max_age' => $announcement->max_age,
                ];

                if ($announcement->type == "whatsapp" && $announcement->file != null) {
                    $data = $this->uploadWhatAppMedia($announcement->file);
                    $media_id = $data?->id;
                }

                // Get the delegates to notify (using chunking for large dataset)
                $delegates = $this->delegateService->listUsers($filter);

                // Process batches of delegates
                $delegates->chunk(500, function ($chunk) use ($announcement, $media_id) {
                    $batchNo = time() . rand(1000, 9999);
                    $smsData = [];

                    foreach ($chunk as $contact) {
                        $smsData[] = [
                            'to' => $contact->phone_number,
                            'sender_id' => settings('yoovi_sms_send_id'),
                            'message' => $announcement->short_message,
                            'file_type' => $announcement->file_type,
                            'tem_type' => $announcement->tem_type,
                            'file' => $announcement->file,
                            'type' => $announcement->type,
                            'media_id' => $media_id,
                            'company_id' => $announcement->company_id,
                            'created_by' => $announcement->created_by,
                            'batch_no' => $batchNo,
                            'is_sent' => 0,
                            'created_from' => 'web',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if (!empty($smsData)) {
                        SmsAlert::insert($smsData);
                        log_activity("Bulk SMS Queued", count($smsData) . " records", "send-quick-sms-successful");
                    }

                    // Optional: Dispatch a job for queued SMS sending (uncomment if necessary)
                    // Queue::push(new SendSmsJob($batchNo, $smsData));
                });
            } catch (\Exception $exception) {
                log_error(format_exception($exception), $announcement, 'send-bulk-sms-failed');
            }
        }

        return '';
    }
}
