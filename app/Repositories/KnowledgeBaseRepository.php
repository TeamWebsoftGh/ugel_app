<?php

namespace App\Repositories;

use App\Models\Resource\KnowledgeBase;
use App\Repositories\Interfaces\IKnowledgeBaseRepository;
use Illuminate\Support\Collection;

class KnowledgeBaseRepository extends BaseRepository implements IKnowledgeBaseRepository
{
    /**
     * KnowledgeBaseRepository constructor.
     *
     * @param KnowledgeBase $topic
     */
    public function __construct(KnowledgeBase $topic)
    {
        parent::__construct($topic);
        $this->model = $topic;
    }

    /**
     * List all the Topics
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $topics
     */
    public function listTopics(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();
        $query->when(!empty($filter['filter_status']), function ($q) use ($filter) {
            $q->where('is_active', $filter['filter_status']);
        });

        $query->when(!empty($filter['filter_category']), function ($q) use ($filter) {
            $q->where('category_id', $filter['filter_category']);
        });
        return $query->orderBy($order, $sort);
    }

    /**
     * Create the Topic
     *
     * @param array $data
     *
     * @return KnowledgeBase
     */
    public function createTopic(array $data): KnowledgeBase
    {
        return $this->create($data);
    }

    /**
     * Find the Topic by id
     *
     * @param int $id
     *
     * @return KnowledgeBase
     */
    public function findTopicById(int $id): KnowledgeBase
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Topic
     *
     * @param array $params
     *
     * @param KnowledgeBase $topic
     * @return bool
     */
    public function updateTopic(array $params, KnowledgeBase $topic): bool
    {
        return $topic->update($params);
    }

    /**
     * @param KnowledgeBase $topic
     * @return bool|null
     * @throws \Exception
     */
    public function deleteTopic(KnowledgeBase $topic)
    {
        return $topic->delete();
    }
}
