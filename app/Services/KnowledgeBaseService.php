<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Common\DocumentUpload;
use App\Models\Resource\KnowledgeBase;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IKnowledgeBaseRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IKnowledgeBaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class KnowledgeBaseService extends ServiceBase implements IKnowledgeBaseService
{
    use UploadableTrait;

    private IKnowledgeBaseRepository $knowledgeBaseRepo;

    /**
     * SubsidiaryService constructor.
     *
     * @param IKnowledgeBaseRepository $knowledgeBaseRepository
     */
    public function __construct(IKnowledgeBaseRepository $knowledgeBaseRepository)
    {
        parent::__construct();
        $this->knowledgeBaseRepo = $knowledgeBaseRepository;
    }

    /**
     * List all the Topics
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listTopics(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->knowledgeBaseRepo->listTopics($order, $sort);
    }

    /**
     * Create the Subsidiary
     *
     * @param array $params
     * @return Response
     */
    public function createTopic(array $params)
    {
        //Declaration
        $topic = null;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['title']);
            $topic = $this->knowledgeBaseRepo->createTopic($params);

            if (isset($params['kb_files'])) {
                $files = collect($params['kb_files']);
                $this->saveDocuments($files, $topic, $topic->title);
            }

        } catch (\Exception $e) {
            log_error(format_exception($e), new KnowledgeBase(), 'create-knowledge-base-failed');
        }

        //Check if Subsidiary was created successfully
        if (!$topic)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-knowledge-base-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $topic, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $topic;

        return $this->response;
    }


    /**
     * Find the Subsidiary by id
     *
     * @param int $id
     *
     * @return KnowledgeBase
     */
    public function findTopicById(int $id): KnowledgeBase
    {
        return $this->knowledgeBaseRepo->findTopicById($id);
    }

    /**
     * Update KnowledgeBase
     *
     * @param array $params
     * @param KnowledgeBase $topic
     * @return Response
     */
    public function updateTopic(array $params, KnowledgeBase $topic)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $params['slug'] = Str::slug($params['title']);
            $result = $this->knowledgeBaseRepo->updateTopic($params, $topic);

            if (isset($params['kb_files'])) {
                $files = collect($params['kb_files']);
                $this->saveDocuments($files, $topic, $topic->title);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $topic, 'update-knowledge-base-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-knowledge-base-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $topic, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $topic;

        return $this->response;
    }

    /**
     * @param KnowledgeBase $topic
     * @return Response
     */
    public function deleteTopic(KnowledgeBase $topic)
    {
        //Declaration
        $result = false;
        try{

            $result = $this->knowledgeBaseRepo->deleteTopic($topic);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $topic, 'delete-knowledge-base-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-knowledge-base-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $topic, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param KnowledgeBase $topic
     * @return Response
     */
    public function deleteDocument(DocumentUpload $document, KnowledgeBase $topic)
    {
        //Declaration
        $result = false;

        try{
            $result = $this->knowledgeBaseRepo->deleteDocument($document);

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $topic, 'delete-knowledge-base-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-knowledge-base-document-successful';
        $auditMessage = "File deleted.";

        log_activity($auditMessage, $topic, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
