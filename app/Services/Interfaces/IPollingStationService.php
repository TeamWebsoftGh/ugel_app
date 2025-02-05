<?php

namespace App\Services\Interfaces;

use App\Models\Delegate\PollingStation;
use Illuminate\Support\Collection;

interface IPollingStationService extends IBaseService
{
    public function listPollingStations(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createPollingStation(array $params);

    public function findPollingStationById(int $id): PollingStation;

    public function updatePollingStation(array $params, PollingStation $pollingStation);

    public function deletePollingStation(PollingStation $pollingStation);
}
