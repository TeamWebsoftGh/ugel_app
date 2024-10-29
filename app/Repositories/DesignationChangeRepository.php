<?php

namespace App\Repositories;

use App\Models\Property\DesignationChange;
use App\Repositories\Interfaces\IDesignationChangeRepository;
use Illuminate\Support\Collection;

class DesignationChangeRepository extends BaseRepository implements IDesignationChangeRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param DesignationChange $designationChange
     */
    public function __construct(DesignationChange $designationChange)
    {
        parent::__construct($designationChange);
        $this->model = $designationChange;
    }

    /**
     * Find the DesignationChange by id
     *
     * @param int $id
     *
     * @return DesignationChange
     */
    public function findDesignationChangeById(int $id): DesignationChange
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return DesignationChange
     */
    public function createDesignationChange(array $data) : DesignationChange
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param DesignationChange $designationChange
     * @return bool
     */
    public function updateDesignationChange(array $data, DesignationChange $designationChange) : bool
    {
        return $this->update($data, $designationChange->id);
    }

    /**
     * @param DesignationChange $designationChange
     * @return bool
     */
    public function deleteDesignationChange(DesignationChange $designationChange) : bool
    {
        return $this->delete($designationChange->id);
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listDesignationChanges(array $filter = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($filter);

        return $result->orderBy($order, $sort)->get($columns);
    }
}
