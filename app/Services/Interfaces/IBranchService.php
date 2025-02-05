<?php

namespace App\Services\Interfaces;

use App\Models\Organization\Branch;
use Illuminate\Support\Collection;

interface IBranchService extends IBaseService
{
    public function listBranches(string $order = 'id', string $sort = 'desc'): Collection;

    public function createBranch(array $params);

    public function findBranchById(int $id) : Branch;

    public function updateBranch(array $params, Branch $branch);

    public function deleteBranch(Branch $branch);
}
