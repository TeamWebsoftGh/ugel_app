<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Election\PoliticalParty;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IPoliticalPartyRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPoliticalPartyService;
use Illuminate\Support\Collection;

class PoliticalPartyService extends ServiceBase implements IPoliticalPartyService
{
    use UploadableTrait;

    private IPoliticalPartyRepository $politicalPartyRepo;

    /**
     * PoliticalPartyService constructor.
     *
     * @param IPoliticalPartyRepository $politicalPartyRepository
     */
    public function __construct(IPoliticalPartyRepository $politicalPartyRepository)
    {
        parent::__construct();
        $this->politicalPartyRepo = $politicalPartyRepository;
    }

    /**
     * List all the Political Parties
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listPoliticalParties(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->politicalPartyRepo->listPoliticalParties($filter, $order, $sort);
    }

    /**
     * Create the Political Party
     *
     * @param array $params
     * @return Response
     */
    public function createPoliticalParty(array $params)
    {
        $politicalParty = null;

        try {
            $politicalParty = $this->politicalPartyRepo->createPoliticalParty($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new PoliticalParty(), 'create-political-party-failed');
        }

        if (!$politicalParty)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        log_activity(ResponseMessage::DEFAULT_SUCCESS_CREATE, $politicalParty, 'create-political-party-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
        $this->response->data = $politicalParty;

        return $this->response;
    }

    /**
     * Find the Political Party by id
     *
     * @param int $id
     *
     * @return PoliticalParty
     */
    public function findPoliticalPartyById(int $id): PoliticalParty
    {
        return $this->politicalPartyRepo->findPoliticalPartyById($id);
    }

    /**
     * Update Political Party
     *
     * @param array $params
     * @param PoliticalParty $politicalParty
     * @return Response
     */
    public function updatePoliticalParty(array $params, PoliticalParty $politicalParty)
    {
        $result = false;

        try {
            $result = $this->politicalPartyRepo->updatePoliticalParty($params, $politicalParty);
        } catch (\Exception $e) {
            log_error(format_exception($e), $politicalParty, 'update-political-party-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        log_activity('You have successfully updated a Political Party ' . $politicalParty->name, $politicalParty, 'update-political-party-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully updated a Political Party ' . $politicalParty->name;
        $this->response->data = $politicalParty;

        return $this->response;
    }

    /**
     * @param PoliticalParty $politicalParty
     * @return Response
     */
    public function deletePoliticalParty(PoliticalParty $politicalParty)
    {
        $result = false;
        try{
            $result = $this->politicalPartyRepo->deletePoliticalParty($politicalParty);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $politicalParty, 'delete-political-party-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        log_activity('You have successfully deleted Political Party ' . $politicalParty->name, $politicalParty, 'delete-political-party-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully deleted Political Party ' . $politicalParty->name;

        return $this->response;
    }
}
