<?php

namespace App\Repositories\Interfaces;

use App\Models\CustomerService\SupportTicket;
use Illuminate\Support\Collection;

interface ISupportTicketRepository extends IBaseRepository
{
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createSupportTicket(array $data) : SupportTicket;

    public function findSupportTicketById(int $id) : SupportTicket;

    public function updateSupportTicket(array $data, SupportTicket $supportTicket) : bool;

    public function deleteSupportTicket(SupportTicket $supportTicket);
}
