<?php

namespace App\Services\Interfaces;

use App\Models\Property\Transfer;
use Illuminate\Support\Collection;

interface ITransferService extends IBaseService
{
    public function listTransfers(array $filter, string $order = 'id', string $sort = 'desc'): Collection;

    public function createTransfer(array $params);

    public function findTransferById(int $id);

    public function findTransferByStaffId(string $staff_id);

    public function updateTransfer(array $params, Transfer $transfer);

    public function deleteTransfer(Transfer $transfer);
}
