<?php

namespace App\Services\Properties;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Property\Room;
use App\Repositories\Interfaces\IRoomRepository;
use App\Services\Helpers\Response;
use App\Services\Properties\Interfaces\IRoomService;
use App\Services\ServiceBase;
use App\Traits\UploadableTrait;

class RoomService extends ServiceBase implements IRoomService
{
    use UploadableTrait;

    private IRoomRepository $roomRepo;

    /**
     * RoomService constructor.
     *
     * @param IRoomRepository $roomRepository
     */
    public function __construct(IRoomRepository $roomRepository)
    {
        parent::__construct();
        $this->roomRepo = $roomRepository;
    }

    /**
     * List all the Rooms
     *
     * @param string $order
     * @param string $sort
     *
     * @return
     */
    public function listRooms(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->roomRepo->listRooms($filter, $order, $sort);
    }

    /**
     * Create the Rooms
     *
     * @param array $params
     * @return Response
     */
    public function createRoom(array $data)
    {
        //Declaration
        $room = null;

        //Process Request
        try {
            $room = $this->roomRepo->createRoom($data);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Room(), 'create-room-failed');
        }

        //Check if Room was created successfully
        if (!$room)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-room-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $room, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $room;

        return $this->response;
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
        return $this->roomRepo->findRoomById($id);
    }

    /**
     * Update Room
     *
     * @param array $params
     * @param Room $room
     * @return Response
     */
    public function updateRoom(array $data, Room $room)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->roomRepo->updateRoom($data, $room);
        } catch (\Exception $e) {
            log_error(format_exception($e), $room, 'update-room-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-room-successful';
        $auditMessage = 'You have successfully updated a Room '.$room->name;

        log_activity($auditMessage, $room, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $room;

        return $this->response;
    }

    /**
     * @param Room $room
     * @return Response
     */
    public function deleteRoom(Room $room)
    {
        //Declaration
        $result = false;
        try{
            $result = $this->roomRepo->deleteRoom($room);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $room, 'delete-room-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-room-successful';
        $auditMessage = 'You have successfully deleted Room '.$room->name;

        log_activity($auditMessage, $room, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
