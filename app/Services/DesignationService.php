<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Organization\Designation;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IDesignationRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IDesignationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DesignationService extends ServiceBase implements IDesignationService
{
    use UploadableTrait;

    private IDesignationRepository $designationRepo;

    /**
     * DesignationService constructor.
     *
     * @param IDesignationRepository $designationRepository
     */
    public function __construct(IDesignationRepository $designationRepository)
    {
        parent::__construct();
        $this->designationRepo = $designationRepository;
    }

    /**
     * List all the Designations
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listDesignations(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->designationRepo->listDesignations($order, $sort);
    }

    /**
     * Create the Designations
     *
     * @param array $params
     * @return Response
     */
    public function createDesignation(array $params)
    {
        //Declaration
        $designation = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['designation_name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'designations' , $params['slug']);
            }
            $designation = $this->designationRepo->createDesignation($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Designation(), 'create-designation-failed');
        }

        //Check if Designation was created successfully
        if (!$designation)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-designation-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $designation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $designation;

        return $this->response;
    }


    /**
     * Find the Designation by id
     *
     * @param int $id
     *
     * @return Designation
     */
    public function findDesignationById(int $id): Designation
    {
        return $this->designationRepo->findDesignationById($id);
    }

    /**
     * Update Designation
     *
     * @param array $params
     * @param Designation $designation
     * @return Response
     */
    public function updateDesignation(array $params, Designation $designation)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['designation_name']);
            if (isset($params['cover_image']) && $params['cover_image'] instanceof UploadedFile) {
                $params['cover_image'] = $this->uploadOne($params['cover_image'],'designations' , $params['slug']);
            }
            $result = $this->designationRepo->updateDesignation($params, $designation);
        } catch (\Exception $e) {
            log_error(format_exception($e), $designation, 'update-designation-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-designation-successful';
        $auditMessage = 'You have successfully updated a Designation '.$designation->designation_name;

        log_activity($auditMessage, $designation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $designation;

        return $this->response;
    }

    /**
     * @param Designation $designation
     * @return Response
     */
    public function deleteDesignation(Designation $designation)
    {
        //Declaration
        $result = false;
        try{
            if (count($designation->users) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_CANNOT_DELETE;;

                return $this->response;
            }

            $result = $this->designationRepo->deleteDesignation($designation);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $designation, 'delete-designation-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-designation-successful';
        $auditMessage = 'You have successfully deleted designation '.$designation->designation_name;

        log_activity($auditMessage, $designation, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
