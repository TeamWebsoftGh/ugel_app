<?php

namespace App\Repositories;

use App\Models\Organization\Designation;
use App\Repositories\Interfaces\IDesignationRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DesignationRepository extends BaseRepository implements IDesignationRepository
{
    /**
     * DesignationRepository constructor.
     *
     * @param Designation $designation
     */
    public function __construct(Designation $designation)
    {
        parent::__construct($designation);
        $this->model = $designation;
    }

    /**
     * List all the Designations
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $designations
     */
    public function listDesignations(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Designation
     *
     * @param array $data
     *
     * @return Designation
     */
    public function createDesignation(array $data): Designation
    {
        return $this->create($data);
    }

    /**
     * Find the Designation by id
     *
     * @param int $id
     *
     * @return Designation
     */
    public function findDesignationById(int $id): Designation
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Designation
     *
     * @param array $params
     *
     * @param Designation $designation
     * @return bool
     */
    public function updateDesignation(array $params, Designation $designation): bool
    {
        return $designation->update($params);
    }

    /**
     * @param Designation $designation
     * @return bool|null
     * @throws \Exception
     */
    public function deleteDesignation(Designation $designation)
    {
        return $designation->delete();
    }
}
