<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource\KnowledgeBase;
use Illuminate\Support\Collection;

interface IKnowledgeBaseRepository extends IBaseRepository
{
    public function listTopics(string $order = 'id', string $sort = 'desc'): Collection;

    public function createTopic(array $params) : KnowledgeBase;

    public function findTopicById(int $id) : KnowledgeBase;

    public function updateTopic(array $params, KnowledgeBase $topic) : bool;

    public function deleteTopic(KnowledgeBase $topic);
}
