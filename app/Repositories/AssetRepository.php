<?php

namespace App\Repositories;

use App\Models\Hrm\Asset;
use App\Repositories\Interfaces\IAssetRepository;
use Illuminate\Support\Collection;

class AssetRepository extends BaseRepository implements IAssetRepository
{
    /**
     * AssetRepository constructor.
     *
     * @param Asset $asset
     */
    public function __construct(Asset $asset)
    {
        parent::__construct($asset);
        $this->model = $asset;
    }

    /**
     * Find the AdmissionPeriod by id
     *
     * @param int $id
     *
     * @return Asset
     */
    public function findAssetById(int $id): Asset
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Asset
     */
    public function createAsset(array $data) : Asset
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Asset $asset
     * @return bool
     */
    public function updateAsset(array $data, Asset $asset) : bool
    {
        return $this->update($data, $asset->id);
    }

    /**
     * @param Asset $asset
     *
     * @return bool
     */
    public function deleteAsset(Asset $asset) : bool
    {
        return $this->delete($asset->id);
    }

    /**
     *
     * @param array|null $filter
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listAssets(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->getFilteredList($filter);

        return $result->orderBy($order, $sort)->get();
    }

}
