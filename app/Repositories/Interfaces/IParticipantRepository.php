<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use App\Models\Guest;
use App\Models\Participant;
use Illuminate\Support\Collection;

interface IParticipantRepository extends IBaseRepository
{
    public function listParticipants(string $order = 'id', string $sort = 'desc'): Collection;

    public function createParticipant(array $params);

    public function findParticipantById(int $id) : Participant;

    public function updateParticipant(array $params, Participant $participant);

    public function deleteParticipant(Participant $participant);
}
