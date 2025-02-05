<?php

namespace App\Repositories\Interfaces;

use App\Models\Client\ClientType;
use Illuminate\Support\Collection;

interface IClientTypeRepository extends IBaseRepository
{
    public function listClientTypes(array $filter = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createClientType(array $params) : ClientType;

    public function findClientTypeById(int $id) : ClientType;

    public function updateClientType(array $params, ClientType $clientType) : bool;

    public function deleteClientType(ClientType $clientType);
}
