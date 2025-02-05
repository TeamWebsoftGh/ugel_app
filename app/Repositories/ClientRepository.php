<?php

namespace App\Repositories;

use App\Models\Client\Client;
use App\Repositories\Interfaces\IClientRepository;
use Illuminate\Support\Collection;

class ClientRepository extends BaseRepository implements IClientRepository
{
    /**
     * ClientRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->model = $client;
    }

    /**
     * List all the Clients
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $clients
     */
    public function listClients(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $result = Client::query();

        if (!empty($filter['filter_client_type']))
        {
            $result = $result->where('client_type_id', $filter['filter_client_type']);
        }

        if (!empty($filter['filter_category']))
        {
            $result = $result->whereHas('clientType', function ($query) use($filter) {
                return $query->where('category', '=', $filter['filter_category']);
            });
        }

        if (!empty($filter['filter_client_code']))
        {
            $result = $result->whereHas('clientType', function ($query) use($filter) {
                return $query->where('code', '=', $filter['filter_client_code']);
            });
        }

        if (!empty($filter['filter_client_type']))
        {
            $result = $result->where('client_type_id', $filter['filter_client_type']);
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('status', $filter['filter_status']);
        }

        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Client
     *
     * @param array $data
     *
     * @return Client
     */
    public function createClient(array $data): Client
    {
        return $this->create($data);
    }

    /**
     * Find the Client by id
     *
     * @param int $id
     *
     * @return Client
     */
    public function findClientById(int $id): Client
    {
        return $this->findOneOrFail($id);
    }


    /**
     * Update Client
     *
     * @param array $data
     * @param Client $client
     * @return bool
     */
    public function updateClient(array $data, Client $client): bool
    {
        return $this->update($data, $client->id);
    }

    public function deleteClient(Client $client): bool
    {
        return $this->delete($client->id);
    }
}
