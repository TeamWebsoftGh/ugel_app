<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Models\Memo\Announcement;
use App\Models\Memo\ContactGroup;
use App\Models\Memo\SmsAlert;
use App\Repositories\Interfaces\IBulkSmsRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IBulkSmsService;
use App\Traits\SmsTrait;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class BulkSmsService extends ServiceBase implements IBulkSmsService
{
    use UploadableTrait, SmsTrait;

    private IBulkSmsRepository $announcementRepo;

    /**
     * SubsidiaryService constructor.
     *
     * @param IBulkSmsRepository $announcementRepository
     */
    public function __construct(IBulkSmsRepository $announcementRepository)
    {
        parent::__construct();
        $this->announcementRepo = $announcementRepository;
    }

    /**
     * List all the Announcements
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listAnnouncements(array $filter = null, string $order = 'id', string $sort = 'desc')
    {
        if(user()->cannot('read-global-access'))
        {
            $filter['created_by'] = user()->id;
        }
        return $this->announcementRepo->listAnnouncements($filter, $order, $sort);
    }

    /**
     * Create the Subsidiary
     *
     * @param array $params
     * @return Response
     */
    public function createAnnouncement(array $params)
    {
        //Declaration
        $announcement = null;
        $batchNo = time().rand(1000,9999);
        $file = null;

        //Process Request
        try {
            $total = settings("voice_main_balance");
            if($params['type'] == "voice" && $total < 1)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You don't have enough balance for voice calls.";

                return $this->response;
            }
            $params['slug'] = Str::slug($params['title']);
            if (isset($params['file']) && $params['file'] instanceof UploadedFile) {
                $file = $this->uploadPublic($params['file'], $batchNo, 'bulk-'.$params['type']);
            }
            $params['file'] = $file;
            $params['created_by'] = user()->id;
            $params['company_id'] = user()->company_id;
            $announcement = $this->announcementRepo->createAnnouncement($params);
            if (isset($params['attachments'])) {
                $files = collect($params['attachments']);
                $this->saveDocuments($files, $announcement, "bulk-sms");
            }

        } catch (\Exception $e) {
            log_error(format_exception($e), new Announcement(), 'create-bulk-sms-failed');
        }

        //Check if Announcement was created successfully
        if (!$announcement)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-bulk-sms-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $announcement, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $announcement;

        return $this->response;
    }


    /**
     * Find the Subsidiary by id
     *
     * @param int $id
     *
     * @return Announcement
     */
    public function findAnnouncementById(int $id): Announcement
    {
        return $this->announcementRepo->findAnnouncementById($id);
    }

    /**
     * Update Announcement
     *
     * @param array $params
     * @param Announcement $announcement
     * @return Response
     */
    public function updateAnnouncement(array $params, Announcement $announcement)
    {
        //Declaration
        $result = false;
        $batchNo = time().rand(1000,9999);


        //Process Request
        try {
            if($announcement->is_sent)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Communication already sent";

                return $this->response;
            }
            $params['slug'] = Str::slug($params['title']);
            if (isset($params['voice_file']) && $params['voice_file'] instanceof UploadedFile) {
                $file = $this->uploadPublic($params['voice_file'], $batchNo, 'bulk-voice');
                $params['file'] = $file;
            }
            if (isset($params['attachments'])) {
                $files = collect($params['attachments']);
                $this->saveDocuments($files, $announcement, "bulk-sms");
            }
            $result = $this->announcementRepo->updateAnnouncement($params, $announcement);
        } catch (\Exception $e) {
            log_error(format_exception($e), $announcement, 'update-bulk-sms-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-bulk-sms-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $announcement, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $announcement;

        return $this->response;
    }

    /**
     * @param Announcement $announcement
     * @return Response
     */
    public function deleteAnnouncement(Announcement $announcement)
    {
        //Declaration
        $result = false;
        try{
            if($announcement->is_sent)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;

                return $this->response;
            }
            $result = $this->announcementRepo->deleteAnnouncement($announcement);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $announcement, 'delete-bulk-sms-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-bulk-sms-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $announcement, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function sendQuickSms(array $params)
    {
        $batchNo = time().rand(1000,9999);
        $file = null;
        $media_id = null;

        try {

            $total = settings("voice_main_balance");
            if($params['type'] == "voice" && $total < 1)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You don't have enough balance for voice calls.";

                return $this->response;
            }

            if (isset($params['file']) && $params['file'] instanceof UploadedFile) {
                $file = $this->uploadPublic($params['file'], $batchNo, 'bulk-'.$params['type']);
                if($params['type'] == "whatsapp")
                {
                    $data = $this->uploadWhatAppMedia($file);
                    $media_id = $data?->id;
                }
            }
            if(isset($params['contact_group_id']))
            {
                $group = ContactGroup::find($params['contact_group_id']);
                $contacts = $group?->contacts;

                if($contacts == null || count($contacts) == 0)
                {
                    $this->response->status = ResponseType::ERROR;
                    $this->response->message = "No contacts available under selected contact group.";

                    return $this->response;
                }

                foreach ($contacts as $contact){
                    try {
                        // Data to be inserted
                        $data = [
                            'to' => $contact->phone_number,
                            'sender_id' => settings('yoovi_sms_send_id'),
                            'message' => isset($params['short_message'])?$params['short_message']:'',
                            'batch_no' => $batchNo,
                            'is_sent' => 0,
                            'type' => $params['type'],
                            'file_type' => isset($params['file_type'])?$params['file_type']:'',
                            'tem_type' => isset($params['tem_type'])?$params['tem_type']:'',
                            'file' => $file,
                            'media_id' => $media_id,
                            'company_id' => user()->company_id,
                            'created_by' => user()->id,
                            'created_from' => 'web',
                        ];

                        // Creating a new record in the sms_alerts table
                        $smsAlert = SmsAlert::create($data);
                        log_activity("Sms Queued", $smsAlert, "send-quick-sms-successful");
                    }catch (\Exception $ex)
                    {
                        log_error(format_exception($ex), new SmsAlert(), 'send-quick-sms-failed');
                    }
                }
            }

            if(isset($params['recipient']))
            {
                // Assume 'items' is a comma-separated string from the request
                $itemsString = $params['recipient'];

                // Convert the comma-separated string to an array
                $itemsArray = explode(',', $itemsString);

                // Optionally, you may want to trim whitespace from each item
                $itemsArray = array_map('trim', $itemsArray);

                // Check if items is an array and not empty
                if (is_array($itemsArray) && count($itemsArray) > 0) {
                    foreach ($itemsArray as $itemData) {
                        // Data to be inserted
                        try {
                            // Data to be inserted
                            $data = [
                                'to' => $itemData,
                                'sender_id' => settings('yoovi_sms_send_id'),
                                'message' => isset($params['short_message'])?$params['short_message']:'',
                                'is_sent' => 0,
                                'batch_no' => $batchNo,
                                'type' => $params['type'],
                                'company_id' => user()->company_id,
                                'created_by' => user()->id,
                                'file' => $file,
                                'media_id' => $media_id,
                                'file_type' => isset($params['file_type'])?$params['file_type']:'',
                                'tem_type' => isset($params['tem_type'])?$params['tem_type']:'',
                                'created_from' => 'web',
                            ];

                            // Creating a new record in the sms_alerts table
                            $smsAlert = SmsAlert::create($data);
                            log_activity("Sms Queued", $smsAlert, "send-quick-sms-successful");
                        }catch (\Exception $ex)
                        {
                            log_error(format_exception($ex), new SmsAlert(), 'send-quick-sms-failed');
                        }
                    }
                }
            }

            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = ResponseMessage::DEFAULT_SUCCESS;

            return $this->response;
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), new SmsAlert(), 'send-quick-sms-failed');
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }
    }
}
