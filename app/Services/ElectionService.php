<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Election\Election;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IElectionRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IElectionService;
use Illuminate\Support\Collection;

class ElectionService extends ServiceBase implements IElectionService
{
    use UploadableTrait;

    private IElectionRepository $electionRepo;

    /**
     * ElectionService constructor.
     *
     * @param IElectionRepository $electionRepository
     */
    public function __construct(IElectionRepository $electionRepository)
    {
        parent::__construct();
        $this->electionRepo = $electionRepository;
    }

    /**
     * List all the Elections
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listElections(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->electionRepo->listElections($filter, $order, $sort);
    }

    /**
     * Create the Election
     *
     * @param array $params
     * @return Response
     */
    public function createElection(array $params)
    {
        $election = null;

        try {
            $election = $this->electionRepo->createElection($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Election(), 'create-election-failed');
        }

        if (!$election)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        log_activity(ResponseMessage::DEFAULT_SUCCESS_CREATE, $election, 'create-election-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
        $this->response->data = $election;

        return $this->response;
    }

    /**
     * Find the Election by id
     *
     * @param int $id
     *
     * @return Election
     */
    public function findElectionById(int $id): Election
    {
        return $this->electionRepo->findElectionById($id);
    }

    /**
     * Update Election
     *
     * @param array $params
     * @param Election $election
     * @return Response
     */
    public function updateElection(array $params, Election $election)
    {
        $result = false;

        try {
            $result = $this->electionRepo->updateElection($params, $election);
        } catch (\Exception $e) {
            log_error(format_exception($e), $election, 'update-election-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        log_activity('You have successfully updated ' . $election->name, $election, 'update-election-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully updated ' . $election->name;
        $this->response->data = $election;

        return $this->response;
    }

    /**
     * @param Election $election
     * @return Response
     */
    public function deleteElection(Election $election)
    {
        $result = false;
        try{
            $result = $this->electionRepo->deleteElection($election);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $election, 'delete-election-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        log_activity('You have successfully deleted ' . $election->name, $election, 'delete-election-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully deleted ' . $election->name;

        return $this->response;
    }
}
