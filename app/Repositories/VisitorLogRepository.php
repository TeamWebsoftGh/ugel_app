<?php

namespace App\Repositories;

use App\Models\CustomerService\VisitorLog;
use App\Repositories\Interfaces\IVisitorLogRepository;
use Illuminate\Support\Collection;

class VisitorLogRepository extends BaseRepository implements IVisitorLogRepository
{
    /**
     * VisitorLogRepository constructor.
     *
     * @param VisitorLog $visitorLog
     */
    public function __construct(VisitorLog $visitorLog)
    {
        parent::__construct($visitorLog);
        $this->model = $visitorLog;
    }

    /**
     * List all the VisitorLogs
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $visitorLogs
     */
    public function listVisitorLogs(array $filter, string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->orderBy($order, $sort)->get();
    }

    /**
     * Create the VisitorLogs
     *
     * @param array $data
     *
     * @return VisitorLog
     */
    public function createVisitorLog(array $data): VisitorLog
    {
        return $this->create($data);
    }

    /**
     * Find the VisitorLog by id
     *
     * @param int $id
     *
     * @return VisitorLog
     */
    public function findVisitorLogById(int $id): VisitorLog
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update VisitorLog
     *
     * @param array $params
     *
     * @param VisitorLog $visitorLog
     * @return bool
     */
    public function updateVisitorLog(array $params, VisitorLog $visitorLog): bool
    {
        return $this->update($params, $visitorLog->id);
    }

    /**
     * @param VisitorLog $visitorLog
     * @return bool|null
     * @throws \Exception
     */
    public function deleteVisitorLog(VisitorLog $visitorLog)
    {
        return $this->delete($visitorLog->id);
    }
}
