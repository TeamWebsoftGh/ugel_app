<?php

namespace App\Repositories\CustomerService;


use App\Models\CustomerService\SupportTopic;
use App\Repositories\BaseRepository;
use App\Repositories\CustomerService\Interfaces\ISupportTopicRepository;

class SupportTopicRepository extends BaseRepository implements ISupportTopicRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param SupportTopic $supportTopic
     */
    public function __construct(SupportTopic $supportTopic)
    {
        parent::__construct($supportTopic);
        $this->model = $supportTopic;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return SupportTopic
     */
    public function findSupportTopicById(int $id): SupportTopic
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return SupportTopic
     */
    public function createSupportTopic(array $data) : SupportTopic
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param SupportTopic $supportTopic
     * @return bool
     */
    public function updateSupportTopic(array $data, SupportTopic $supportTopic) : bool
    {
        return $supportTopic->update($data);
    }

    /**
     * @param SupportTopic $supportTopic
     * @return bool
     */
    public function deleteSupportTopic(SupportTopic $supportTopic) : bool
    {
        return $supportTopic->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     */
    public function listSupportTopics(array $filter = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*'])
    {
        $result = $this->model->query();

        if (!empty($filter['filter_status']))
        {
            $result = $result->where('is_active', $filter['filter_status']);
        }

        if (!empty($filter['filter_name']))
        {
            $result = $result->where('name', 'like', '%'.$filter['filter_name'].'%');
        }

        return $result->orderBy($order, $sort);
    }

}
