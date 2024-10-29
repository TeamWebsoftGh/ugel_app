<?php

namespace App\Services\Interfaces;

use App\Models\ApplicationUser;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

interface IMessageService extends IBaseService
{
    public function listMessage(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function listOrderMessages(int $orderId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listWriterMessages(int $writerId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listCustomerMessages(int $customerId, string $order = 'id', string $sort = 'desc'): Collection;

    public function listUserMessages(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createMessage(array $params);

    public function findMessageById(int $id) : Message;

    public function updateMessage(array $params, Message $Message);

    public function changeMessageStatus(int $id);

    public function deleteMessage(Message $Message);
}
