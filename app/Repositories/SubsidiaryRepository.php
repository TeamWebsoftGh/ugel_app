<?php

namespace App\Repositories;

use App\Models\Organization\Subsidiary;
use App\Repositories\Interfaces\ISubsidiaryRepository;
use Illuminate\Support\Collection;

class SubsidiaryRepository extends BaseRepository implements ISubsidiaryRepository
{
    /**
     * SubsidiaryRepository constructor.
     *
     * @param Subsidiary $subsidiary
     */
    public function __construct(Subsidiary $subsidiary)
    {
        parent::__construct($subsidiary);
        $this->model = $subsidiary;
    }

    /**
     * List all the Subsidiaries
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $subsidiaries
     */
    public function listSubsidiaries(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Subsidiary
     *
     * @param array $data
     *
     * @return Subsidiary
     */
    public function createSubsidiary(array $data): Subsidiary
    {
        return $this->create($data);
    }

    /**
     * Find the Subsidiary by id
     *
     * @param int $id
     *
     * @return Subsidiary
     */
    public function findSubsidiaryById(int $id): Subsidiary
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Subsidiary
     *
     * @param array $params
     *
     * @param Subsidiary $subsidiary
     * @return bool
     */
    public function updateSubsidiary(array $params, Subsidiary $subsidiary): bool
    {
        return $subsidiary->update($params);
    }

    /**
     * @param Subsidiary $subsidiary
     * @return bool|null
     * @throws \Exception
     */
    public function deleteSubsidiary(Subsidiary $subsidiary)
    {
        return $subsidiary->delete();
    }
}
