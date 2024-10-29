<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization\Branch;
use Illuminate\Support\Collection;

interface IBranchRepository extends IBaseRepository
{
    public function listBranches(string $order = 'id', string $sort = 'desc'): Collection;

    public function createBranch(array $params) : Branch;

    public function findBranchById(int $id) : Branch;

    public function updateBranch(array $params, Branch $branch) : bool;

    public function deleteBranch(Branch $branch);
}
