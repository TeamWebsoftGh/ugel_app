<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use App\Models\Institution;
use Illuminate\Support\Collection;

interface IInstitutionRepository extends IBaseRepository
{
    public function listInstitution(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createInstitution(array $params) : Institution;

    public function findInstitutionById(int $id) : Institution;

    public function updateInstitution(array $params, int $id) : bool;

    public function deleteInstitution(int $id);
}
