<?php

namespace App\Services\Interfaces;

use App\Models\IncomeTax;

interface IIncomeTaxService extends IBaseService
{
    public function listIncomeTaxes();

    public function createIncomeTax(array $params);

    public function findIncomeTaxById($id);

    public function taxTable($id);

    public function updateIncomeTax(array $params, IncomeTax $incomeTax);

    public function deleteIncomeTax(IncomeTax $incomeTax);

    public function createUpdateIncomeTaxTable(array $data, IncomeTax $incomeTax);

    public function deleteIncomeTaxTable(int $id);
}
