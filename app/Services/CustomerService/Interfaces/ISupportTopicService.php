<?php

namespace App\Services\CustomerService\Interfaces;

use App\Models\CustomerService\SupportTopic;
use App\Services\Interfaces\IBaseService;

interface ISupportTopicService extends IBaseService
{
    public function listSupportTopics(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*']);

    public function createSupportTopic(array $data);

    public function findSupportTopicById(int $id);

    public function updateSupportTopic(array $data, SupportTopic $supportTopic);

    public function deleteSupportTopic(SupportTopic $supportTopic);

    public function deleteMultiple(array $ids);
}
