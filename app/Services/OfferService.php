<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Offer;
use App\Repositories\Interfaces\IOfferRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IOfferService;
use Illuminate\Support\Collection;

class OfferService extends ServiceBase implements IOfferService
{
    private IOfferRepository $offerRepo;

    /**
     * OfferService constructor.
     *
     * @param IOfferRepository $offerRepository
     */
    public function __construct(IOfferRepository $offerRepository)
    {
        parent::__construct();
        $this->offerRepo = $offerRepository;
    }

    /**
     * List all the Offers
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listOffers(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        if(!user()->can('read-offers'))
        {
            $filter['filter_user'] = user()->id;
        }
        return $this->offerRepo->listOffers($filter, $order, $sort);
    }

    /**
     * Create Offer
     *
     * @param array $data
     *
     * @return Response
     */
    public function createOffer(array $data)
    {
        //Declaration
        $offer = null;

        //Process Request
        try {
            $offer = $this->offerRepo->createOffer($data);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Offer(), 'create-property-failed');
        }

        //Check if Offer was created successfully
        if (!$offer)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $offer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $offer;

        return $this->response;
    }


    /**
     * Find the Offer by id
     *
     * @param int $id
     *
     * @return Offer
     */
    public function findOfferById(int $id)
    {
        return $this->offerRepo->findOfferById($id);
    }


    /**
     * Update Offer
     *
     * @param array $params
     *
     * @param Offer $offer
     * @return Response
     */
    public function updateOffer(array $params, Offer $offer)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->offerRepo->updateOffer($params, $offer);
        } catch (\Exception $e) {
            log_error(format_exception($e), $offer, 'update-property-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $offer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param Offer $offer
     * @return Response
     */
    public function deleteOffer(Offer $offer)
    {
        //Declaration
        $result =false;

        try{
            $result = $this->offerRepo->deleteOffer($offer);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $offer, 'delete-property-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-property-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $offer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
