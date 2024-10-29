<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Memo\Contact;
use App\Repositories\Interfaces\IContactRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IContactService;
use Illuminate\Support\Collection;

class ContactService extends ServiceBase implements IContactService
{

    private IContactRepository $contactRepo;

    /**
     * FacilitatorService constructor.
     *
     * @param IContactRepository $contactRepository
     */
    public function __construct(IContactRepository $contactRepository){
        parent::__construct();
        $this->contactRepo = $contactRepository;
    }

    /**
     * List all the Contact Us Messages
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listContacts(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->contactRepo->listContacts($filter, $order, $sort);
    }

    /**
     * Create the Contact Us
     *
     * @param array $params
     * @return Response
     */
    public function createContact(array $params)
    {
        //Declaration
        $contact = null;

        //Process Request
        try {
            $contact = $this->contactRepo->createContact($params);

        } catch (\Exception $e) {
            log_error(format_exception($e), new Contact(), 'create-contact-failed');
        }

        //Check if contact-us was created successfully
        if (!$contact || $contact == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-contact-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $contact, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $contact;

        return $this->response;
    }

    /**
     * Find the contact-us form by id
     *
     * @param int $id
     *
     * @return Contact
     */
    public function findContactById(int $id): Contact
    {
        return $this->contactRepo->findContactById($id);
    }

    /**
     * Update contact-us
     *
     * @param array $params
     * @param Contact $contact
     * @return Response
     */
    public function updateContact(array $params, Contact $contact)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->contactRepo->updateContact($params, $contact);

        } catch (\Exception $e) {
            log_error(format_exception($e), $contact, 'update-contact-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-contact-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $contact, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param Contact $contact
     * @return Response
     */
    public function deleteContact(Contact $contact)
    {
        //Declaration
        $result = $this->contactRepo->delete($contact->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-contact-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $contact, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
