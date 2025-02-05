<?php

namespace App\Services\Interfaces;

use App\Models\CustomerService\SupportTicket;
use Illuminate\Support\Collection;

interface ISupportTicketService extends IBaseService
{
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createSupportTicket(array $params);

    public function findSupportTicketById(int $id) : SupportTicket;

    public function updateSupportTicket(array $params, SupportTicket $supportTicket);

    public function deleteSupportTicket(SupportTicket $supportTicket);

    public function getCreateTicket();
}
