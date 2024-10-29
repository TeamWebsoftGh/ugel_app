<?php

namespace App\Repositories;

use App\Models\Property\Offense;
use App\Repositories\Interfaces\IOffenseRepository;
use Illuminate\Support\Collection;

class OffenseRepository extends BaseRepository implements IOffenseRepository
{
    /**
     * OffenseRepository constructor.
     *
     * @param Offense $offense
     */
    public function __construct(Offense $offense)
    {
        parent::__construct($offense);
        $this->model = $offense;
    }

    /**
     * Find the Offense by id
     *
     * @param int $id
     *
     * @return Offense
     */
    public function findOffenseById(int $id): Offense
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Offense
     */
    public function createOffense(array $data) : Offense
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Offense $offense
     * @return bool
     */
    public function updateOffense(array $data, Offense $offense) : bool
    {
        return $this->update($data, $offense->id);
    }

    /**
     * @param Offense $offense
     *
     * @return bool
     */
    public function deleteOffense(Offense $offense) : bool
    {
        return $this->delete($offense->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listOffenses(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($params);

        return $result->orderBy($order, $sort)->get($columns);
    }

}
