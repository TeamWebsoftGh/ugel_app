<?php

namespace App\Repositories\Interfaces;

use App\Models\Client\Client;
use Illuminate\Support\Collection;

interface IClientRepository extends IBaseRepository
{
    public function listClients(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createClient(array $data) : Client;

    public function findClientById(int $id) : Client;

    public function updateClient(array $data, Client $client) : bool;
    public function deleteClient(Client $client) : bool;
}
