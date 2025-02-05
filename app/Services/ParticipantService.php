<?php

namespace App\Services;

use App\Enums\ResponseType;
use App\Models\Participant;
use App\Repositories\Interfaces\IParticipantRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IParticipantService;
use Illuminate\Support\Collection;

class ParticipantService extends ServiceBase implements IParticipantService
{
    private $participantRepo;

    /**
     * ParticipantService constructor.
     *
     * @param IParticipantRepository $participantRepository
     */
    public function __construct(IParticipantRepository $participantRepository)
    {
        $this->participantRepo = $participantRepository;
    }

    /**
     * List all the Participants
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listParticipants(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->participantRepo->listParticipants($order, $sort);
    }

    /**
     * Create the Participants
     *
     * @param array $params
     * @return Response
     */
    public function createParticipant(array $params)
    {
        //Declaration
        $result = new Response();
        $participant = null;

        //Process Request
        try {
            $participant = $this->participantRepo->createParticipant($params);
        } catch (\Exception $e) {
            log_error($e->getMessage(), new Participant(), 'create-participant-failed');
        }

        //Check if Participant was created successfully
        if (!$participant || $participant == null)
        {
            $result->status = ResponseType::ERROR;
            $result->message = "An error occurred. Try Again Later";

            return $result;
        }

        //Audit Trail
        $logAction = 'create-participant-successful';
        $auditMessage = 'Participant Successfully added with name: '.$participant->full_name;

        log_activity($auditMessage, $participant, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;
        $result->data = $participant;

        return $result;
    }

    /**
     * Find the Participant by id
     *
     * @param int $id
     *
     * @return Participant
     */
    public function findParticipantById(int $id): Participant
    {
        return $this->participantRepo->findParticipantById($id);
    }

    /**
     * Update Participant
     *
     * @param array $params
     * @param Participant $participant
     * @return Response
     */
    public function updateParticipant(array $params, Participant $participant)
    {
        //Declaration
        $result = new Response();

        //Process Request
        try {
            $this->participantRepo->updateParticipant($params, $participant);
        } catch (\Exception $e) {
            log_error($e->getMessage(), $participant, 'update-participant-failed');
        }

        //Audit Trail
        $logAction = 'update-participant-successful';
        $auditMessage = 'Participant successfully updated with name: '.$participant->full_name;

        log_activity($auditMessage, $participant, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }

    /**
     * @param Participant $participant
     * @return Response
     */
    public function deleteParticipant(Participant $participant)
    {
        //Declaration
        $result = new Response();

        //$participant->bookings()->sync([]);
        $this->participantRepo->deleteParticipant($participant);

        //Audit Trail
        $logAction = 'delete-participant-successful';
        $auditMessage = 'You have successfully deleted Participant with name '.$participant->full_name;

        log_activity($auditMessage, $participant, $logAction);
        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;

        return $result;
    }
}
