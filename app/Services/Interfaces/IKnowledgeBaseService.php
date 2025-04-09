<?php

namespace App\Services\Interfaces;

use App\Models\Common\DocumentUpload;
use App\Models\Resource\KnowledgeBase;
use Illuminate\Support\Collection;

interface IKnowledgeBaseService extends IBaseService
{
    public function listTopics(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createTopic(array $data);

    public function findTopicById(int $id) : KnowledgeBase;

    public function updateTopic(array $data, KnowledgeBase $topic);

    public function deleteTopic(KnowledgeBase $topic);

    public function deleteMultiple(array $ids);
}

