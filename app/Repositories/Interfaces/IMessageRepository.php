<?php

namespace App\Repositories\Interfaces;

use App\Models\Message;
use Illuminate\Support\Collection;

interface IMessageRepository extends IBaseRepository
{
    public function listMessages(string $order = 'id', string $sort = 'desc'): Collection;

    public function listUserMessages(int $userId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listWriterMessages(int $userId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listCustomerMessages(int $userId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listMessagesByCategory(int $categoryId, string $order = 'id', string $sort = 'desc'): Collection;

    public function createMessage(array $params) : Message;

    public function findMessageById(int $id) : Message;

    public function updateMessage(array $params, Message $message) : bool;

    public function deleteMessage(Message $message);
}
