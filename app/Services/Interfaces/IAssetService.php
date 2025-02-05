<?php

namespace App\Services\Interfaces;

use App\Models\Hrm\Asset;
use Illuminate\Support\Collection;

interface IAssetService extends IBaseService
{
    public function listAssets(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createAsset(array $params);

    public function updateAsset(array $params, Asset $asset);

    public function findAssetById(int $id);

    public function deleteAsset(Asset $asset);
}
