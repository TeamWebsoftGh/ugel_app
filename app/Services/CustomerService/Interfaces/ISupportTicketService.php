<?php

namespace App\Services\CustomerService\Interfaces;

use App\Models\CustomerService\SupportTicket;
use App\Services\Interfaces\IBaseService;

interface ISupportTicketService extends IBaseService
{
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createSupportTicket(array $params);

    public function findSupportTicketById(int $id) : SupportTicket;

    public function updateSupportTicket(array $params, SupportTicket $supportTicket);

    public function deleteSupportTicket(SupportTicket $supportTicket);

    public function getCreateTicket();

    public function deleteMultiple(array $ids);
}
