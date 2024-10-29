<?php

namespace App\Repositories\Interfaces;

use App\Models\Memo\Event;
use Illuminate\Support\Collection;

interface IEventRepository extends IBaseRepository
{
    public function listEvents(string $order = 'id', string $sort = 'desc', $except = []) : Collection;

    public function createEvent(array $params) : Event;

    public function updateEvent(array $params, Event $event) : Event;

    public function findEventById(int $id) : Event;

    public function deleteEvent(Event $event) : bool;
}
