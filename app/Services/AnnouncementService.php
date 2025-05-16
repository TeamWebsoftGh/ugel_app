<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Models\Communication\Announcement;
use App\Models\Communication\ContactGroup;
use App\Models\Communication\SmsAlert;
use App\Notifications\AnnouncementPublished;
use App\Repositories\Interfaces\IAnnouncementRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IAnnouncementService;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AnnouncementService extends ServiceBase implements IAnnouncementService
{
    use UploadableTrait;

    private IAnnouncementRepository $announcementRepo;

    /**
     * SubsidiaryService constructor.
     *
     * @param IAnnouncementRepository $announcementRepository
     */
    public function __construct(IAnnouncementRepository $announcementRepository)
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
    public function listAnnouncements(array $params = null, string $order = 'id', string $sort = 'desc')
    {
        return $this->announcementRepo->listAnnouncements($params, $order, $sort);
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

        //Process Request
        try {
            $params['slug'] = Str::slug($params['title']);
            $announcement = $this->announcementRepo->createAnnouncement($params);
            if (isset($params['attachments'])) {
                $files = collect($params['attachments']);
                $this->saveDocuments($files, $announcement, "announcement");
            }

            $notifiable = User::where('is_active', 1);

            Notification::send($notifiable->get(), new AnnouncementPublished($announcement));

        } catch (\Exception $e) {
            log_error(format_exception($e), new Announcement(), 'create-announcement-failed');
        }

        //Check if Announcement was created successfully
        if (!$announcement)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-announcement-successful';
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

        //Process Request
        try {
            $params['slug'] = Str::slug($params['title']);
            if (isset($params['attachments'])) {
                $files = collect($params['attachments']);
                $this->saveDocuments($files, $announcement, "announcement");
            }
            $result = $this->announcementRepo->updateAnnouncement($params, $announcement);
        } catch (\Exception $e) {
            log_error(format_exception($e), $announcement, 'update-announcement-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-announcement-successful';
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
            $result = $this->announcementRepo->deleteAnnouncement($announcement);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $announcement, 'delete-announcement-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-announcement-successful';
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

        try {
            if(isset($params['contact_group_id']))
            {
                $group = ContactGroup::find($params['contact_group_id']);
                $contacts = $group?->contacts;

                if($contacts == null || count($contacts) == 0)
                {
                    $this->response->status = ResponseType::ERROR;
                    $this->response->message = "No contacts selected.";

                    return $this->response;
                }

                foreach ($contacts as $contact){
                    try {
                        // Data to be inserted
                        $data = [
                            'to' => $contact->phone_number,
                            'sender_id' => settings('yoovi_sms_send_id'),
                            'message' => $params['short_message'],
                            'batch_no' => $batchNo,
                            'is_sent' => 0,
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
                                'message' => $params['short_message'],
                                'is_sent' => 0,
                                'batch_no' => $batchNo,
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
