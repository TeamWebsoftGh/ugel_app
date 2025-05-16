<?php

namespace App\Repositories\CustomerService\Interfaces;

use App\Models\CustomerService\SupportTicket;
use App\Repositories\Interfaces\IBaseRepository;

interface ISupportTicketRepository extends IBaseRepository
{
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createSupportTicket(array $data) : SupportTicket;

    public function findSupportTicketById(int $id) : SupportTicket;

    public function updateSupportTicket(array $data, SupportTicket $supportTicket) : bool;

    public function deleteSupportTicket(SupportTicket $supportTicket);
}
