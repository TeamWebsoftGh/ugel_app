<?php

namespace App\Repositories\Interfaces;

use App\Models\Delegate\PollingStation;
use Illuminate\Support\Collection;

interface IPollingStationRepository extends IBaseRepository
{
    public function listPollingStations(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createPollingStation(array $params) : PollingStation;

    public function findPollingStationById(int $id) : PollingStation;

    public function updatePollingStation(array $params, PollingStation $pollingStation) : bool;

    public function deletePollingStation(PollingStation $pollingStation);
}
