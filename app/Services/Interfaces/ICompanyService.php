<?php

namespace App\Services\Interfaces;

use App\Models\Organization\Company;
use Illuminate\Support\Collection;

interface ICompanyService extends IBaseService
{
    public function listCompanies(string $order = 'id', string $sort = 'desc', $columns = []) : Collection;

    public function createCompany(array $params);

    public function updateCompany(array $params, Company $Company);

    public function findCompanyById(int $id);

    public function deleteCompany(Company $Company);

}
