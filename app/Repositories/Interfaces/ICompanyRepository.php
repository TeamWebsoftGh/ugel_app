<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization\Company;
use Illuminate\Support\Collection;

interface ICompanyRepository extends IBaseRepository
{
    public function listCompanies(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function createCompany(array $params) : Company;

    public function findCompanyById(int $id) : Company;

    public function updateCompany(array $params, int $id) : bool;

    public function deleteCompany(int $id);
}
