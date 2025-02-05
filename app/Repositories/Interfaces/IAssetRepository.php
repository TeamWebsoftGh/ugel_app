<?php

namespace App\Repositories\Interfaces;

use App\Models\Hrm\Asset;
use Illuminate\Support\Collection;

interface IAssetRepository extends IBaseRepository
{
    public function findAssetById(int $id);

    public function listAssets(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createAsset(array $params);

    public function updateAsset(array $params, Asset $asset);

    public function deleteAsset(Asset $asset);
}
