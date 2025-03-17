<?php

namespace App\Services\Properties\Interfaces;

use App\Models\Property\Room;
use App\Services\Interfaces\IBaseService;

interface IRoomService extends IBaseService
{
    public function listRooms(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createRoom(array $data);

    public function findRoomById(int $id) : Room;

    public function updateRoom(array $data, Room $room);

    public function deleteRoom(Room $room);
}
