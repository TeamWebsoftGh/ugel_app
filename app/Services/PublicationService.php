<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Resource\Category;
use App\Models\Resource\Publication;
use App\Repositories\Interfaces\IPublicationRepository;
use App\Repositories\PublicationRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IPublicationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicationService  extends ServiceBase implements IPublicationService
{
    private $publicationRepo;

    /**
     * PublicationService constructor.
     *
     * @param IPublicationRepository $publicationRepository
     */
    public function __construct(IPublicationRepository $publicationRepository){
        parent::__construct();
        $this->publicationRepo = $publicationRepository;
    }

    /**
     * List all the Categories
     *
     * @param bool $paginate
     * @param int $perPage
     * @param string $orderBy
     * @param string $sortBy
     * @param array $columns
     * @param array|null $where
     * @return Collection
     */
    public function listPublications(array $params = null, string $order = 'id', string $sort = 'desc')
    {
        if(!user()->can('read-resources')){
            $params['filter_department'] = employee()->department_id;
            $params['filter_subsidiary'] = employee()->subsidiary_id;
        }
        return $this->publicationRepo->listPublications($params, $order, $sort);
    }

    /**
     * Create Category
     *
     * @param array $params
     *
     * @return Response
     */
    public function createPublication(array $params)
    {
        //Declaration
        $publication = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['title']).'-'.time();
            $params['type'] = optional(Category::find($params['category_id']))->name;

            if (isset($params['file']) && $params['file'] instanceof UploadedFile) {
                $params['file_path'] = $params['file']->storeAs("publications/".$params['type'], $params['slug'] . "." . $params['file']->getClientOriginalExtension(), "public");
            }

            $publication = $this->publicationRepo->createPublication($params);

        } catch (\Exception $e) {
            log_error(format_exception($e), new Publication(), 'create-publication-failed');
        }

        //Check if Publication was created successfully
        if (!$publication)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-publication-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $publication, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $publication;

        return $this->response;
    }


    /**
     * Find the Category by id
     *
     * @param int $id
     *
     * @return Publication
     */
    public function findCPublicationById(int $id)
    {
        return $this->publicationRepo->findPublicationById($id);
    }

    /**
     * Update Publication
     *
     * @param array $params
     *
     * @param Publication $publication
     * @return Response
     */
    public function updatePublication(array $params, Publication $publication)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['type'] = optional(Category::find($params['category_id']))->name;
            $params['slug'] = Str::slug($params['title'].'-'.time());

            if (isset($params['file']) && $params['file'] instanceof UploadedFile) {
                if(isset($publication->file)){
                    $this->publicationRepo->deleteCoverImage($publication);
                }
                $params['file_path'] = $params['file']->storeAs("publications/".$params['type'], $params['slug'] . "." . $params['file']->getClientOriginalExtension(), "public");
            }

            $publicationRepo = new PublicationRepository($publication);
            $result = $publicationRepo->updatePublication($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), $publication, 'update-publication-failed');
        }

        //Check if Career was created successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-publication-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $publication, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Publication $publication
     * @return Response
     */
    public function deletePublication(Publication $publication)
    {
        //Declaration
        $result = false;
        try {
            $this->publicationRepo->deleteCoverImage($publication);
            $result = $this->publicationRepo->deletePublication($publication);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $publication, 'delete-publication-failed');
        }

        //Check if Publication was created successfully
        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-publication-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $publication, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveFile(UploadedFile $file) : string
    {
        return $file->store('publications', ['disk' => 'public']);
    }
}
