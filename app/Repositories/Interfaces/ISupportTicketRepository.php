<?php

namespace App\Repositories\Interfaces;

use App\Models\CustomerService\SupportTicket;
use Illuminate\Support\Collection;

interface ISupportTicketRepository extends IBaseRepository
{
    public function listSupportTickets(array $params = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createSupportTicket(array $params) : SupportTicket;

    public function findSupportTicketById(int $id) : SupportTicket;

    public function updateSupportTicket(array $params, SupportTicket $supportTicket) : bool;

    public function deleteSupportTicket(SupportTicket $supportTicket);
}
