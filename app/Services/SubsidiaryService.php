<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Subsidiary;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\ISubsidiaryRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ISubsidiaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SubsidiaryService extends ServiceBase implements ISubsidiaryService
{
    use UploadableTrait;

    private ISubsidiaryRepository $subsidiaryRepo;

    /**
     * SubsidiaryService constructor.
     *
     * @param ISubsidiaryRepository $subsidiaryRepository
     */
    public function __construct(ISubsidiaryRepository $subsidiaryRepository)
    {
        parent::__construct();
        $this->subsidiaryRepo = $subsidiaryRepository;
    }

    /**
     * List all the Subsidiarys
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listSubsidiaries(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->subsidiaryRepo->listSubsidiaries($order, $sort);
    }

    /**
     * Create the Subsidiary
     *
     * @param array $params
     * @return Response
     */
    public function createSubsidiary(array $params)
    {
        //Declaration
        $subsidiary = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'subsidiaries' , $params['slug']);
            }
            $subsidiary = $this->subsidiaryRepo->createSubsidiary($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Subsidiary(), 'create-subsidiary-failed');
        }

        //Check if Subsidiary was created successfully
        if (!$subsidiary)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-subsidiary-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $subsidiary, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $subsidiary;

        return $this->response;
    }


    /**
     * Find the Subsidiary by id
     *
     * @param int $id
     *
     * @return Subsidiary
     */
    public function findSubsidiaryById(int $id): Subsidiary
    {
        return $this->subsidiaryRepo->findSubsidiaryById($id);
    }

    /**
     * Update Subsidiary
     *
     * @param array $params
     * @param Subsidiary $subsidiary
     * @return Response
     */
    public function updateSubsidiary(array $params, Subsidiary $subsidiary)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadPublic($params['cover_image'],'subsidiaries' , $params['slug']);
            }
            $result = $this->subsidiaryRepo->updateSubsidiary($params, $subsidiary);
        } catch (\Exception $e) {
            log_error(format_exception($e), $subsidiary, 'update-subsidiary-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-subsidiary-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $subsidiary, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $subsidiary;

        return $this->response;
    }

    /**
     * @param Subsidiary $subsidiary
     * @return Response
     */
    public function deleteSubsidiary(Subsidiary $subsidiary)
    {
        //Declaration
        $result = false;
        try{
            if (count($subsidiary->users) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Subsidiary.";

                return $this->response;
            }

            $result = $this->subsidiaryRepo->deleteSubsidiary($subsidiary);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $subsidiary, 'delete-subsidiary-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-subsidiary-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $subsidiary, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
