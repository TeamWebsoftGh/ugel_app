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
    private IKnowledgeBaseRepository $knowledgeBaseRepo;

    /**
     * KnowledgeBaseService constructor.
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
    public function listTopics(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->knowledgeBaseRepo->listTopics($filter, $order, $sort);
    }

    /**
     * Create the Subsidiary
     *
     * @param array $data
     * @return Response
     */
    public function createTopic(array $data)
    {
        //Declaration
        $data['slug'] = Str::slug($data['title']);
        $topic = $this->knowledgeBaseRepo->createTopic($data);
        return $this->buildCreateResponse($topic);
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
        return $this->knowledgeBaseRepo->findOneOrFail($id);
    }

    /**
     * Update KnowledgeBase
     *
     * @param array $params
     * @param KnowledgeBase $topic
     * @return Response
     */
    public function updateTopic(array $data, KnowledgeBase $topic)
    {
        $data['slug'] = Str::slug($data['title']);
        $result = $this->knowledgeBaseRepo->update($data, $topic->id);

        return $this->buildUpdateResponse($topic, $result);
    }

    /**
     * @param KnowledgeBase $topic
     * @return Response
     */
    public function deleteTopic(KnowledgeBase $topic)
    {
        $result = $this->knowledgeBaseRepo->delete($topic->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->knowledgeBaseRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
