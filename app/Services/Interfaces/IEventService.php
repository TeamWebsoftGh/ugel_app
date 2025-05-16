<?php

namespace App\Services\Interfaces;

use App\Models\Communication\Event;
use Illuminate\Support\Collection;

interface IEventService extends IBaseService
{
    public function listEvents(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createEvent(array $params);

    public function findEventById(int $id);

    public function updateEvent(array $params, Event $Event);

    public function deleteEvent(Event $Event);
}
