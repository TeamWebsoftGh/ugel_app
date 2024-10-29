<?php

namespace App\Repositories;

use App\Models\Organization\Branch;
use App\Repositories\Interfaces\IBranchRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class BranchRepository extends BaseRepository implements IBranchRepository
{
    /**
     * BranchRepository constructor.
     *
     * @param Branch $branch
     */
    public function __construct(Branch $branch)
    {
        parent::__construct($branch);
        $this->model = $branch;
    }

    /**
     * List all the Branchs
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $branchs
     */
    public function listBranches(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the Branch
     *
     * @param array $data
     *
     * @return Branch
     */
    public function createBranch(array $data): Branch
    {
        return $this->create($data);
    }

    /**
     * Find the Branch by id
     *
     * @param int $id
     *
     * @return Branch
     */
    public function findBranchById(int $id): Branch
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Branch
     *
     * @param array $params
     *
     * @param Branch $branch
     * @return bool
     */
    public function updateBranch(array $params, Branch $branch): bool
    {
        return $branch->update($params);
    }

    /**
     * @param Branch $branch
     * @return bool|null
     * @throws \Exception
     */
    public function deleteBranch(Branch $branch)
    {
        return $branch->delete();
    }
}
