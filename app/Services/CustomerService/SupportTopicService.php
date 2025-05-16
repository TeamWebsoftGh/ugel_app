<?php

namespace App\Services\CustomerService;

use App\Models\CustomerService\SupportTopic;
use App\Repositories\CustomerService\Interfaces\ISupportTopicRepository;
use App\Services\CustomerService\Interfaces\ISupportTopicService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Traits\UploadableTrait;
use Illuminate\Support\Collection;

class SupportTopicService extends ServiceBase implements ISupportTopicService
{
    use UploadableTrait;
    private ISupportTopicRepository $supportTopicRepo;

    /**
     * SupportTopicService constructor.
     * @param ISupportTopicRepository $supportTopic
     */
    public function __construct(ISupportTopicRepository $supportTopic)
    {
        parent::__construct();
        $this->supportTopicRepo = $supportTopic;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listSupportTopics(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc', array $columns = ['*'])
    {
        return $this->supportTopicRepo->listSupportTopics($filter, $orderBy, $sortBy);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function createSupportTopic(array $data)
    {
        //Declaration
        $supportTopic = $this->supportTopicRepo->createSupportTopic($data);
        return $this->buildCreateResponse($supportTopic);
    }


    /**
     * @param array $data
     * @param SupportTopic $supportTopic
     * @return Response
     */
    public function updateSupportTopic(array $data, SupportTopic $supportTopic)
    {
        $result = $this->supportTopicRepo->updateSupportTopic($data, $supportTopic);
        return $this->buildUpdateResponse($supportTopic, $result);
    }


    /**
     * @param int $id
     * @return SupportTopic|null
     */
    public function findSupportTopicById(int $id)
    {
        return $this->supportTopicRepo->findSupportTopicById($id);
    }


    /**
     * @param SupportTopic $supportTopic
     * @return Response
     */
    public function deleteSupportTopic(SupportTopic $supportTopic)
    {
        $result = $this->supportTopicRepo->deleteSupportTopic($supportTopic);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiple(array $ids)
    {
        //Declaration
        $result = $this->supportTopicRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
