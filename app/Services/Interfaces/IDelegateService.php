<?php

namespace App\Services\Interfaces;

use App\Models\Delegate\Delegate;
use Illuminate\Support\Collection;

interface IDelegateService extends IBaseService
{
    public function listDelegates(array $filter = null, string $order = 'updated_at', string $sort = 'desc');

    public function createDelegate(array $params);

    public function findDelegateById(int $id): Delegate;

    public function updateDelegate(array $params, Delegate $delegate);

    public function deleteDelegate(Delegate $delegate);
}
