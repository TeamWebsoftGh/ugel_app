<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Room;

interface IRoomRepository extends IBaseRepository
{
    public function listRooms(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createRoom(array $data) : Room;

    public function findRoomById(int $id) : Room;

    public function updateRoom(array $data, Room $room) : bool;

    public function deleteRoom(Room $room);
}
