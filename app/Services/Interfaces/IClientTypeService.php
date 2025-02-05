<?php

namespace App\Services\Interfaces;

use App\Models\Client\ClientType;
use Illuminate\Support\Collection;

interface IClientTypeService extends IBaseService
{
    public function listClientTypes(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']) : Collection;

    public function createClientType(array $data);

    public function findClientTypeById(int $id);

    public function updateClientType(array $data, ClientType $clientType);

    public function deleteClientType(ClientType $clientType);
}
