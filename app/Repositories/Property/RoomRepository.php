<?php

namespace App\Repositories\Property;

use App\Models\Property\Room;
use App\Repositories\BaseRepository;
use App\Repositories\Property\Interfaces\IRoomRepository;

class RoomRepository extends BaseRepository implements IRoomRepository
{
    /**
     * RoomRepository constructor.
     *
     * @param Room $room
     */
    public function __construct(Room $room)
    {
        parent::__construct($room);
        $this->model = $room;
    }

    /**
     * List all the Rooms
     *
     * @param string $order
     * @param string $sort
     *
     * @return $rooms
     */
    public function listRooms(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $result = $this->model->query();

        if (!empty($filter['filter_property_type'])) {
            $result = $result->whereHas('propertyUnit.property', function ($query) use ($filter) {
                $query->where('property_type_id', $filter['filter_property_type']);
            });
        }

        if (!empty($filter['filter_property'])) {
            $result = $result->whereHas('propertyUnit', function ($query) use ($filter) {
                $query->where('property_id', $filter['filter_property']);
            });
        }

        if (!empty($filter['filter_property_unit']))
        {
            $result = $result->where('property_unit_id', $filter['filter_property_unit']);
        }


        if (!empty($filter['filter_name']))
        {
            $result = $result->where('unit_name', 'like', '%'.$filter['filter_name'].'%');
        }

        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Room
     *
     * @param array $data
     *
     * @return Room
     */
    public function createRoom(array $data): Room
    {
        return $this->create($data);
    }

    /**
     * Find the Room by id
     *
     * @param int $id
     *
     * @return Room
     */
    public function findRoomById(int $id): Room
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Room
     *
     * @param array $data
     * @param Room $room
     * @return bool
     */
    public function updateRoom(array $data, Room $room): bool
    {
        return $this->update($data, $room->id);
    }

    /**
     * @param Room $room
     * @return bool|null
     * @throws \Exception
     */
    public function deleteRoom(Room $room)
    {
        return $this->delete($room->id);
    }
}
