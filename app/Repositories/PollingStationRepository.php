<?php

namespace App\Repositories;

use App\Models\Delegate\PollingStation;
use App\Repositories\Interfaces\IPollingStationRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class PollingStationRepository extends BaseRepository implements IPollingStationRepository
{
    /**
     * PollingStationRepository constructor.
     *
     * @param PollingStation $pollingStation
     */
    public function __construct(PollingStation $pollingStation)
    {
        parent::__construct($pollingStation);
        $this->model = $pollingStation;
    }

    /**
     * List all the PollingStations
     *
     * @param array|null $filter
     * @param string $order
     * @param string $sort
     *
     * @return Collection $pollingStations
     */
    public function listPollingStations(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        if (!empty($filter['filter_electoral_area']))
        {
            $result = $result->where('electoral_area_id', $filter['filter_electoral_area']);
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('is_active', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('name', 'like', '%'.$filter['filter_name'].'%');
        }

        if (!empty($filter['filter_constituency']))
        {
            $result = $result->whereHas('electoral_area', function ($query) use($filter) {
                return $query->where('constituency_id', '=', $filter['filter_constituency']);
            });
        }

        if (!empty($filter['filter_region']))
        {
            $result = $result->whereHas('electoral_area.constituency', function ($query) use($filter) {
                return $query->where('region_id', '=', $filter['filter_region']);
            });
        }

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the PollingStation
     *
     * @param array $data
     *
     * @return PollingStation
     */
    public function createPollingStation(array $data): PollingStation
    {
        return $this->create($data);
    }

    /**
     * Find the PollingStation by id
     *
     * @param int $id
     *
     * @return PollingStation
     */
    public function findPollingStationById(int $id): PollingStation
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update PollingStation
     *
     * @param array $params
     * @param PollingStation $pollingStation
     * @return bool
     */
    public function updatePollingStation(array $params, PollingStation $pollingStation): bool
    {
        return $pollingStation->update($params);
    }

    /**
     * @param PollingStation $pollingStation
     * @return bool|null
     * @throws \Exception
     */
    public function deletePollingStation(PollingStation $pollingStation)
    {
        return $pollingStation->delete();
    }
}
