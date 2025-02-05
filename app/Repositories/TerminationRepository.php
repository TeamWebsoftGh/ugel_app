<?php

namespace App\Repositories;

use App\Models\Property\Termination;
use App\Repositories\Interfaces\ITerminationRepository;
use Illuminate\Support\Collection;

class TerminationRepository extends BaseRepository implements ITerminationRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param Termination $termination
     */
    public function __construct(Termination $termination)
    {
        parent::__construct($termination);
        $this->model = $termination;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return Termination
     */
    public function findTerminationById(int $id): Termination
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Termination
     */
    public function createTermination(array $data) : Termination
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Termination $termination
     * @return bool
     */
    public function updateTermination(array $data, Termination $termination) : bool
    {
        return $termination->update($data);
    }

    /**
     * @param Termination $termination
     * @return bool
     */
    public function deleteTermination(Termination $termination) : bool
    {
        return $termination->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listTerminations(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($params);

        return $result->orderBy($order, $sort)->get($columns);
    }

}
