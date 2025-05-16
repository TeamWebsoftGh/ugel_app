<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource\KnowledgeBase;
use Illuminate\Support\Collection;

interface IKnowledgeBaseRepository extends IBaseRepository
{
    public function listTopics(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
