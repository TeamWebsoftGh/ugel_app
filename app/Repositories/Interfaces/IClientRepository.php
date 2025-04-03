<?php

namespace App\Repositories\Interfaces;

use App\Models\Client\Client;
use Illuminate\Support\Collection;

interface IClientRepository extends IBaseRepository
{
    public function listClients(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

}
