<?php

namespace App\Services\Interfaces;

use App\Models\Client\Client;
use Illuminate\Support\Collection;

interface IClientService extends IBaseService
{
    public function listClients(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createClient(array $data);

    public function findClientById(int $id) : Client;

    public function updateClient(array $data, Client $client);

    public function deleteClient(Client $client);

    public function getCreateClient();
}
