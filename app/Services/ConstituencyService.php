<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Delegate\Constituency;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IConstituencyRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IConstituencyService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ConstituencyService extends ServiceBase implements IConstituencyService
{
    use UploadableTrait;

    private IConstituencyRepository $constituencyRepo;

    /**
     * ConstituencyService constructor.
     *
     * @param IConstituencyRepository $constituencyRepository
     */
    public function __construct(IConstituencyRepository $constituencyRepository)
    {
        parent::__construct();
        $this->constituencyRepo = $constituencyRepository;
    }

    /**
     * List all the Constituencies
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listConstituencies(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
//        if(!user()->can('read-regions'))
//        {
//            $filter['filter_region'] = user()->region_id;
//        }
        return $this->constituencyRepo->listConstituencies($filter, $order, $sort);
    }

    /**
     * Create the Constituency
     *
     * @param array $params
     * @return Response
     */
    public function createConstituency(array $params): Response
    {
        //Declaration
        $constituency = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'Constituencies' , $params['slug']);
            }
            $constituency = $this->constituencyRepo->createConstituency($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Constituency(), 'create-constituency-failed');
        }

        //Check if Constituency was created successfully
        if (!$constituency)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-constituency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $constituency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $constituency;

        return $this->response;
    }


    /**
     * Find the Constituency by id
     *
     * @param int $id
     *
     * @return Constituency
     */
    public function findConstituencyById(int $id): Constituency
    {
        return $this->constituencyRepo->findConstituencyById($id);
    }

    /**
     * Update Constituency
     *
     * @param array $params
     * @param Constituency $constituency
     * @return Response
     */
    public function updateConstituency(array $params, Constituency $constituency)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'Constituencies' , $params['slug']);
            }
            $result = $this->constituencyRepo->updateConstituency($params, $constituency);
        } catch (\Exception $e) {
            log_error(format_exception($e), $constituency, 'update-constituency-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-constituency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $constituency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $constituency;

        return $this->response;
    }

    /**
     * @param Constituency $constituency
     * @return Response
     */
    public function deleteConstituency(Constituency $constituency)
    {
        //Declaration
        $result = false;
        try{
            if (count($constituency->electoral_areas) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Constituency. Delete all electoral areas under Constituency first.";

                return $this->response;
            }

            $result = $this->constituencyRepo->deleteConstituency($constituency);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $constituency, 'delete-constituency-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-constituency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $constituency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
