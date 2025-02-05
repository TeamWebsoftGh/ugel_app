<?php

namespace App\Services\Interfaces;

use App\Models\Common\DocumentUpload;
use App\Models\Resource\KnowledgeBase;
use Illuminate\Support\Collection;

interface IKnowledgeBaseService extends IBaseService
{
    public function listTopics(string $order = 'id', string $sort = 'desc'): Collection;

    public function createTopic(array $params);

    public function findTopicById(int $id) : KnowledgeBase;

    public function updateTopic(array $params, KnowledgeBase $topic);

    public function deleteTopic(KnowledgeBase $topic);

    public function deleteDocument(DocumentUpload $document, KnowledgeBase $topic);
}

