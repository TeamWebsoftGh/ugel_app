<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Hrm\Asset;
use App\Repositories\Interfaces\IAssetRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IAssetService;
use Illuminate\Support\Collection;

class AssetService extends ServiceBase implements IAssetService
{
    private IAssetRepository $assetRepo;

    /**
     * AssetService constructor.
     *
     * @param IAssetRepository $assetRepository
     */
    public function __construct(IAssetRepository $assetRepository)
    {
        parent::__construct();
        $this->assetRepo = $assetRepository;
    }

    /**
     * List all the Assets
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listAssets(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        if(!user()->can('read-assets'))
        {
            $filter['filter_employee'] = employee()->id;
        }
        return $this->assetRepo->listAssets($filter, $order, $sort);
    }

    /**
     * Create Asset
     *
     * @param array $params
     *
     * @return Response
     */
    public function createAsset(array $params)
    {
        //Declaration
        $asset = null;

        //Process Request
        try {
            $asset = $this->assetRepo->createAsset($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Asset(), 'create-asset-failed');
        }

        //Check if Asset was created successfully
        if (!$asset)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-asset-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $asset, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $asset;

        return $this->response;
    }


    /**
     * Find the Asset by id
     *
     * @param int $id
     *
     * @return Asset
     */
    public function findAssetById(int $id)
    {
        return $this->assetRepo->findAssetById($id);
    }


    /**
     * Update Asset
     *
     * @param array $params
     *
     * @param Asset $asset
     * @return Response
     */
    public function updateAsset(array $params, Asset $asset)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->assetRepo->updateAsset($params, $asset);
        } catch (\Exception $e) {
            log_error(format_exception($e), $asset, 'update-asset-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-asset-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $asset, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    /**
     * @param Asset $asset
     * @return Response
     */
    public function deleteAsset(Asset $asset)
    {
        //Declaration
        $result =false;

        try{
            $result = $this->assetRepo->deleteAsset($asset);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $asset, 'create-asset-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-asset-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $asset, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
