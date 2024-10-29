<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Transfer;
use Illuminate\Support\Collection;

interface ITransferRepository extends IBaseRepository
{
    public function findTransferById(int $id);

    public function listTransfers(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createTransfer(array $params);

    public function updateTransfer(array $params, Transfer $transfer);

    public function deleteTransfer(Transfer $transfer);
}
