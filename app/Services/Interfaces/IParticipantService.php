<?php

namespace App\Services\Interfaces;

use App\Models\Participant;
use Illuminate\Support\Collection;

interface IParticipantService extends IBaseService
{
    public function listParticipants(string $order = 'id', string $sort = 'desc'): Collection;

    public function createParticipant(array $params);

    public function findParticipantById(int $id) : Participant;

    public function updateParticipant(array $params, Participant $participant);

    public function deleteParticipant(Participant $participant);
}
