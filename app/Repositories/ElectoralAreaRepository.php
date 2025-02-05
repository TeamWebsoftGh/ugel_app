<?php

namespace App\Repositories;

use App\Models\Delegate\ElectoralArea;
use App\Repositories\Interfaces\IElectoralAreaRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class ElectoralAreaRepository extends BaseRepository implements IElectoralAreaRepository
{
    /**
     * ElectoralAreaRepository constructor.
     *
     * @param ElectoralArea $electoralArea
     */
    public function __construct(ElectoralArea $electoralArea)
    {
        parent::__construct($electoralArea);
        $this->model = $electoralArea;
    }

    /**
     * List all the ElectoralAreas
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $electoralAreas
     */
    public function listElectoralAreas(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->model->query();
        if (!empty($filter['filter_constituency']))
        {
            $result = $result->where('constituency_id', $filter['filter_constituency']);
        }

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('is_active', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('name', 'like', '%'.$filter['filter_name'].'%');
        }

        if (!empty($params['filter_region']))
        {
            $result = $result->whereHas('constituency', function ($query) use($filter) {
                return $query->where('region_id', '=', $filter['filter_region']);
            });
        }

        return $result->orderBy($order, $sort)->get();
    }

    /**
     * Create the ElectoralArea
     *
     * @param array $data
     *
     * @return ElectoralArea
     */
    public function createElectoralArea(array $data): ElectoralArea
    {
        return $this->create($data);
    }

    /**
     * Find the ElectoralArea by id
     *
     * @param int $id
     *
     * @return ElectoralArea
     */
    public function findElectoralAreaById(int $id): ElectoralArea
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ElectoralArea
     *
     * @param array $params
     *
     * @param ElectoralArea $electoralArea
     * @return bool
     */
    public function updateElectoralArea(array $params, ElectoralArea $electoralArea): bool
    {
        return $electoralArea->update($params);
    }

    /**
     * @param ElectoralArea $electoralArea
     * @return bool|null
     * @throws \Exception
     */
    public function deleteElectoralArea(ElectoralArea $electoralArea)
    {
        return $electoralArea->delete();
    }
}
