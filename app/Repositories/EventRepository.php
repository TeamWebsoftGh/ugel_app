<?php

namespace App\Repositories;

use App\Models\Memo\Event;
use App\Repositories\Interfaces\IEventRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class EventRepository extends BaseRepository implements IEventRepository
{
    /**
     * EventRepository constructor.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        parent::__construct($event);
        $this->model = $event;
    }

    /**
     * List all the Events
     *
     * @param string $order
     * @param string $sort
     * @param array $except
     * @return Collection
     */
    public function listEvents(string $order = 'id', string $sort = 'desc', $except = []) : Collection
    {
        return $this->model->orderBy($order, $sort)->get()->except($except);
    }

    /**
     * Create the Event
     *
     * @param array $params
     *
     * @return Event
     */
    public function createEvent(array $params) : Event
    {
        $event = new Event($params);

        $event->save();

        return $event;
    }

    /**
     * Update the Event
     *
     * @param array $params
     *
     * @param Event $event
     * @return Event
     */
    public function updateEvent(array $params, Event $event) : Event
    {
        $event->update($params);
        return $event;
    }

    /**
     * @param int $id
     * @return Event
     * @throws ModelNotFoundException
     */
    public function findEventById(int $id) : Event
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Delete a Event
     *
     * @param Event $event
     * @return bool
     * @throws Exception
     */
    public function deleteEvent(Event $event) : bool
    {
        return $this->delete($event->id);
    }
}
