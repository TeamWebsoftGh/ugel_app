<?php

namespace App\Repositories;

use App\Models\Participant;
use App\Repositories\Interfaces\IParticipantRepository;
use Illuminate\Support\Collection;

class ParticipantRepository extends BaseRepository implements IParticipantRepository
{
    /**
     * ParticipantRepository constructor.
     *
     * @param Participant $participant
     */
    public function __construct(Participant $participant)
    {
        parent::__construct($participant);
        $this->model = $participant;
    }

    /**
     * List all the Participants
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $Participants
     */
    public function listParticipants(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Participants
     *
     * @param array $data
     *
     * @return Participant
     */
    public function createParticipant(array $data): Participant
    {
        return $this->create($data);
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
        return $this->findOneOrFail($id);
    }

    /**
     * Update Participant
     *
     * @param array $params
     *
     * @param Participant $participant
     * @return bool
     */
    public function updateParticipant(array $params, Participant $participant): bool
    {
        return $participant->update($params);
    }

    /**
     * @param Participant $participant
     * @return bool|null
     * @throws \Exception
     */
    public function deleteParticipant(Participant $participant)
    {
        return $participant->delete();
    }
}
