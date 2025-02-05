<?php

namespace App\Repositories\Interfaces;

use App\Models\IncomeTax;
use Illuminate\Support\Collection;

interface IIncomeTaxRepository extends IBaseRepository
{
    public function updateIncomeTax(array $params, IncomeTax $IncomeTax);

    public function listIncomeTaxes(string $order = 'id', string $sort = 'desc') : Collection;

    public function createIncomeTax(array $params) : IncomeTax;

    public function findIncomeTaxById(int $id) : IncomeTax;

    public function deleteIncomeTax(IncomeTax $incomeTax);

}
