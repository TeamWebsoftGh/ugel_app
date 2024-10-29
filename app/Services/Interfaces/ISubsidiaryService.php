<?php

namespace App\Services\Interfaces;

use App\Models\Organization\Subsidiary;
use Illuminate\Support\Collection;

interface ISubsidiaryService extends IBaseService
{
    public function listSubsidiaries(string $order = 'id', string $sort = 'desc'): Collection;

    public function createSubsidiary(array $params);

    public function findSubsidiaryById(int $id) : Subsidiary;

    public function updateSubsidiary(array $params, Subsidiary $subsidiary);

    public function deleteSubsidiary(Subsidiary $subsidiary);
}
