<?php

namespace App\Services\CustomerService;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\CustomerService\Enquiry;
use App\Repositories\CustomerService\Interfaces\IEnquiryRepository;
use App\Services\CustomerService\Interfaces\IEnquiryService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;

class EnquiryService extends ServiceBase implements IEnquiryService
{
    private IEnquiryRepository $enquiryRepo;

    /**
     * FacilitatorService constructor.
     *
     * @param IEnquiryRepository $enquiryRepository
     */
    public function __construct(IEnquiryRepository $enquiryRepository){
        parent::__construct();
        $this->enquiryRepo = $enquiryRepository;
    }

    /**
     * List all the Contact Us Messages
     *
     * @param string $order
     * @param string $sort
     *
     * @return
     */
    public function listEnquiryMessages(array $filter = [], string $order = 'id', string $sort = 'desc')
    {
        return $this->enquiryRepo->listEnquiryMessages($filter, $order, $sort);
    }

    /**
     * Create the Contact Us
     *
     * @param array $params
     * @return Response
     */
    public function createEnquiry(array $params)
    {
        //Declaration
        $enquiry = null;

        //Process Request
        try {
            $enquiry = $this->enquiryRepo->createEnquiry($params);

        } catch (\Exception $e) {
            log_error(format_exception($e), new Enquiry(), 'create-enquiry-failed');
        }

        //Check if contact-us was created successfully
        if (!$enquiry)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-enquiry-successful';
        $auditMessage = 'You have successfully sent a new enquiry with title: '.$enquiry->title;

        log_activity($auditMessage, $enquiry, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $enquiry;

        return $this->response;
    }

    /**
     * Find the contact-us form by id
     *
     * @param int $id
     *
     * @return Enquiry
     */
    public function findEnquiryById(int $id): Enquiry
    {
        return $this->enquiryRepo->findEnquiryById($id);
    }

    /**
     * Update contact-us
     *
     * @param array $params
     * @param Enquiry $enquiry
     * @return Response
     */
    public function updateEnquiry(array $params, Enquiry $enquiry)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->enquiryRepo->updateEnquiry($params, $enquiry);

        } catch (\Exception $e) {
            log_error(format_exception($e), $enquiry, 'update-enquiry-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-enquiry-successful';
        $auditMessage = 'You have successfully updated inquiry with title: '.$enquiry->title;

        log_activity($auditMessage, $enquiry, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * Update contact-us
     *
     * @param bool $status
     * @param Enquiry $enquiry
     * @return Response
     */
    public function changeStatus(bool $status, Enquiry $enquiry)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->enquiryRepo->updateEnquiry(['status' => $status], $enquiry);

        } catch (\Exception $e) {
            log_error(format_exception($e), $enquiry, 'update-enquiry-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = 'update-enquiry-successful';
        $auditMessage = 'You have successfully '.$changeAction.' a enquiry with title: '.$enquiry->title;

        log_activity($auditMessage, $enquiry, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Enquiry $enquiry
     * @return Response
     */
    public function deleteEnquiry(Enquiry $enquiry)
    {
        //Declaration
        $result = false;

        $result = $this->enquiryRepo->delete($enquiry->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-enquiry-successful';
        $auditMessage = 'You have successfully deleted enquiry with title '.$enquiry->title;

        log_activity($auditMessage, $enquiry, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
