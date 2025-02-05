<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\ContactUsFeedback;
use App\Models\ContactUs;
use App\Repositories\Interfaces\IContactUsRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IContactUsService;
use Illuminate\Support\Collection;

class ContactUsService extends ServiceBase implements IContactUsService
{

    private $contactUsRepo;

    /**
     * FacilitatorService constructor.
     *
     * @param IContactUsRepository $contactUsRepository
     */
    public function __construct(IContactUsRepository $contactUsRepository){
        parent::__construct();
        $this->contactUsRepo = $contactUsRepository;
    }

    /**
     * List all the Contact Us Messages
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listContactUsMessages(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->contactUsRepo->listContactUsMessages($order, $sort);
    }

    /**
     * Create the Contact Us
     *
     * @param array $params
     * @return Response
     */
    public function createContactUs(array $params)
    {
        //Declaration
        $contactUs = null;

        //Process Request
        try {
            $contactUs = $this->contactUsRepo->createContactUs($params);

            event(new ContactUsFeedback($contactUs));

        } catch (\Exception $e) {
            log_error(format_exception($e), new ContactUs(), 'create-contact-us-failed');
        }

        //Check if contact-us was created successfully
        if (!$contactUs || $contactUs == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-contact-us-successful';
        $auditMessage = 'You have successfully sent a new inquiry with title: '.$contactUs->title;

        log_activity($auditMessage, $contactUs, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $contactUs;

        return $this->response;
    }

    /**
     * Find the contact-us form by id
     *
     * @param int $id
     *
     * @return ContactUs
     */
    public function findContactUsById(int $id): ContactUs
    {
        return $this->contactUsRepo->findContactUsById($id);
    }

    /**
     * Update contact-us
     *
     * @param array $params
     * @param ContactUs $contactUs
     * @return Response
     */
    public function updateContactUs(array $params, ContactUs $contactUs)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->contactUsRepo->updateContactUs($params, $contactUs);

        } catch (\Exception $e) {
            log_error(format_exception($e), $contactUs, 'update-contact-us-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-contact-us-successful';
        $auditMessage = 'You have successfully updated inquiry with title: '.$contactUs->title;

        log_activity($auditMessage, $contactUs, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * Update contact-us
     *
     * @param bool $status
     * @param ContactUs $contactUs
     * @return Response
     */
    public function changeStatus(bool $status, ContactUs $contactUs)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->contactUsRepo->updateContactUs(['status' => $status], $contactUs);

        } catch (\Exception $e) {
            log_error(format_exception($e), $contactUs, 'update-contact-us-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = 'update-contact-us-successful';
        $auditMessage = 'You have successfully '.$changeAction.' a enquiry with title: '.$contactUs->title;

        log_activity($auditMessage, $contactUs, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param ContactUs $contactUs
     * @return Response
     */
    public function deleteContactUs(ContactUs $contactUs)
    {
        //Declaration
        $result = false;

        $result = $this->contactUsRepo->delete($contactUs->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-contact-us-successful';
        $auditMessage = 'You have successfully deleted contact-us with title '.$contactUs->title;

        log_activity($auditMessage, $contactUs, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
