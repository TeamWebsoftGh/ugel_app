<?php

namespace App\Repositories;

use App\Models\CustomerService\SupportTicket;
use App\Repositories\Interfaces\ISupportTicketRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

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
                    return $query->where('user_id', user()->id);
                })->orWhere('user_id', user()->id);
            });
        }

        if (!empty($params['filter_department']))
        {
            $result = $result->whereHas('user', function ($query) use($params) {
                return $query->where('department_id', '=', $params['filter_department']);
            });
        }

        if (!empty($params['filter_subsidiary']))
        {
            $result = $result->whereHas('user', function ($query) use($params) {
                return $query->where('subsidiary_id', '=', $params['filter_subsidiary']);
            });
        }

        if (!empty($params['filter_status']))
        {
            $result = $result->where('status_id', $params['filter_status']);
        }

        if (!empty($params['filter_assignee']))
        {
            $result = $result->whereHas('assignees', function ($query) use ($params) {
                return $query->where('id', $params['filter_assignee']);
            });
        }

        if (!empty($params['filter_user']))
        {
            $result = $result->where('user_id', $params['filter_user']);
        }

        if (!empty($params['filter_start_date']))
        {
            $result = $result->where('created_at', '>=', $params['filter_start_date']);
        }

        if (!empty($params['filter_end_date']))
        {
            $result = $result->where('created_at', '<=', $params['filter_end_date']);
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
