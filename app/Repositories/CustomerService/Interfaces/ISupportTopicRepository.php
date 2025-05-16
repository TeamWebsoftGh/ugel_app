<?php

namespace App\Repositories\CustomerService\Interfaces;

use App\Models\CustomerService\SupportTopic;
use App\Repositories\Interfaces\IBaseRepository;

interface ISupportTopicRepository extends IBaseRepository
{
    public function listSupportTopics(array $filter = [], string $order = 'updated_at', string $sort = 'desc');

    public function createSupportTopic(array $data) : SupportTopic;

    public function findSupportTopicById(int $id) : SupportTopic;

    public function updateSupportTopic(array $data, SupportTopic $supportTopic) : bool;

    public function deleteSupportTopic(SupportTopic $supportTopic);

}
