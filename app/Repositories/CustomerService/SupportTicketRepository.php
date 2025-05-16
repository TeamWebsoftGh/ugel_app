<?php

namespace App\Repositories\CustomerService;

use App\Models\CustomerService\SupportTicket;
use App\Repositories\BaseRepository;
use App\Repositories\CustomerService\Interfaces\ISupportTicketRepository;
use Illuminate\Support\Collection;

class SupportTicketRepository extends BaseRepository implements ISupportTicketRepository
{
    /**
     * SupportTicketRepository constructor.
     *
     * @param SupportTicket $supportTicket
     */
    public function __construct(SupportTicket $supportTicket)
    {
        parent::__construct($supportTicket);
        $this->model = $supportTicket;
    }

    /**
     * List all the SupportTickets
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $supportTickets
     */
    public function listSupportTickets(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $result = SupportTicket::query();

        if(!user()->can('read-support-tickets'))
        {
            $result = $result->where(function ($query) {
                return $query->whereHas('assignees', function ($query) {
                    return $query->where('id', user()->id);
                })->orWhere('created_by', user()->id);
            });
        }

        if (!empty($params['filter_customer_type']))
        {
            $result = $result->whereHas('client', function ($query) use($filter) {
                return $query->where('client_type_id', '=', $filter['filter_customer_type']);
            });
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('status_id', $filter['filter_status']);
        }

        if (!empty($filter['filter_priority']))
        {
            $result = $result->where('priority_id', $filter['filter_priority']);
        }

        if (!empty($filter['filter_category']))
        {
            $result = $result->where('support_topic_id', $filter['filter_category']);
        }

        if (!empty($filter['filter_customer']))
        {
            $result = $result->where('client_id', $filter['filter_customer']);
        }

        if (!empty($filter['filter_assignee']))
        {
            $result = $result->whereHas('assignees', function ($query) use ($filter) {
                return $query->where('id', $filter['filter_assignee']);
            });
        }

        if (!empty($filter['filter_user']))
        {
            $result = $result->where('user_id', $filter['filter_user']);
        }

        if (!empty($filter['filter_start_date']))
        {
            $result = $result->where('created_at', '>=', $filter['filter_start_date']);
        }

        if (!empty($filter['filter_end_date']))
        {
            $result = $result->where('created_at', '<=', $filter['filter_end_date']);
        }

        return $result->orderBy($order, $sort);
    }

    /**
     * Create the SupportTicket
     *
     * @param array $data
     *
     * @return SupportTicket
     */
    public function createSupportTicket(array $data): SupportTicket
    {
        return $this->create($data);
    }

    /**
     * Find the SupportTicket by id
     *
     * @param int $id
     *
     * @return SupportTicket
     */
    public function findSupportTicketById(int $id): SupportTicket
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update SupportTicket
     *
     * @param array $params
     *
     * @param SupportTicket $supportTicket
     * @return bool
     */
    public function updateSupportTicket(array $data, SupportTicket $supportTicket): bool
    {
        return $this->update($data, $supportTicket->id);
    }

    /**
     * @param SupportTicket $supportTicket
     * @return bool|null
     * @throws \Exception
     */
    public function deleteSupportTicket(SupportTicket $supportTicket)
    {
        return $this->delete($supportTicket->id);
    }
}
