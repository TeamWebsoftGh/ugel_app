<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\Interfaces\IMessageRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository extends BaseRepository implements IMessageRepository
{
    /**
     * MessageRepository constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        parent::__construct($message);
        $this->model = $message;
    }

    /**
     * List all the Messages
     *
     * @param string $order
     * @param string $sort
     *
     * @return $users
     */
    public function listMessages(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }


    public function listMessagesByCategory(int $categoryId, string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->listMessages()->whereIn("message_category_id",  [1, $categoryId]);
    }

    /**
     * @param int $writerId
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listWriterMessages(int $writerId, string $order = 'id', string $sort = 'desc'): Collection
    {
        return DB::table('messages')
            ->where('message_category_id',1)
            ->orWhere(function($query) use ($writerId){
                $query->where('receiver_type', "App\Models\Writer")
                    ->where('receiver_id', '==', $writerId);
            })
            ->orWhere(function($query){
                $query->where('message_category_id', 4)
                    ->where('receiver_id', '==', null);
            })
            ->get();
    }

    /**
     * @param int $customerId
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listCustomerMessages(int $customerId, string $order = 'id', string $sort = 'desc'): Collection
    {
        return DB::table('messages')
            ->where('message_category_id', '==', 1)
            ->orWhere(function($query) use ($customerId){
                $query->where('receiver_type', "App\Models\Customer")
                    ->where('receiver_id', '==', $customerId);
            })
            ->orWhere(function($query){
                $query->where('message_category_id', 3)
                    ->where('receiver_id', '==', null);
            })
            ->get();
    }

    /**
     * @param int $userId
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listUserMessages(int $userId, string $order = 'id', string $sort = 'desc'): Collection
    {
        return DB::table('messages')
            ->where('message_category_id', '==', 1)
            ->orWhere(function($query) use ($userId){
                $query->where('receiver_type', "App\Models\User")
                    ->where('receiver_id', '==', $userId);
            })
            ->orWhere(function($query){
                $query->where('message_category_id', 3)
                    ->where('receiver_id', '==', null);
            })
            ->get();
    }

    /**
     * Create the Message
     *
     * @param array $data
     *
     * @return Message
     */
    public function createMessage(array $data): Message
    {
        return $this->create($data);
    }

    /**
     * Find the Message by id
     *
     * @param int $id
     *
     * @return Message
     */
    public function findMessageById(int $id): Message
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Message
     *
     * @param array $params
     *
     * @param Message $message
     * @return bool
     */
    public function updateMessage(array $params, Message $message): bool
    {
        return $message->update($params);
    }

    /**
     * @param Message $message
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMessage(Message $message)
    {
        return $message->delete();
    }
}
