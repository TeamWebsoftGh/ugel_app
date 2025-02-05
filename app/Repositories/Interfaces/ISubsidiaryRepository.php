<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization\Subsidiary;
use Illuminate\Support\Collection;

interface ISubsidiaryRepository extends IBaseRepository
{
    public function listSubsidiaries(string $order = 'id', string $sort = 'desc'): Collection;

    public function createSubsidiary(array $params) : Subsidiary;

    public function findSubsidiaryById(int $id) : Subsidiary;

    public function updateSubsidiary(array $params, Subsidiary $subsidiary) : bool;

    public function deleteSubsidiary(Subsidiary $subsidiary);
}
