<?php

namespace App\Repositories;

use App\Models\CustomerService\MaintenanceRequest;
use App\Repositories\Interfaces\IMaintenanceRepository;
use Illuminate\Support\Collection;

class MaintenanceRepository extends BaseRepository implements IMaintenanceRepository
{
    /**
     * MaintenanceRepository constructor.
     *
     * @param MaintenanceRequest $maintenance
     */
    public function __construct(MaintenanceRequest $maintenance)
    {
        parent::__construct($maintenance);
        $this->model = $maintenance;
    }

    /**
     * List all the Maintenances
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection $maintenances
     */
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $result = MaintenanceRequest::query();

        if(!user()->can('read-maintenance'))
        {
            $result = $result->where(function ($query) {
                return $query->whereHas('assignees', function ($query) {
                    return $query->where('user_id', user()->id);
                })->orWhere('user_id', user()->id);
            });
        }

        if (!empty($params['filter_department']))
        {
            $result = $result->whereHas('user', function ($query) use($params) {
                return $query->where('department_id', '=', $params['filter_department']);
            });
        }

        if (!empty($params['filter_subsidiary']))
        {
            $result = $result->whereHas('user', function ($query) use($params) {
                return $query->where('subsidiary_id', '=', $params['filter_subsidiary']);
            });
        }

        if (!empty($params['filter_status']))
        {
            $result = $result->where('status_id', $params['filter_status']);
        }

        if (!empty($params['filter_assignee']))
        {
            $result = $result->whereHas('assignees', function ($query) use ($params) {
                return $query->where('id', $params['filter_assignee']);
            });
        }

        if (!empty($params['filter_user']))
        {
            $result = $result->where('user_id', $params['filter_user']);
        }

        if (!empty($params['filter_start_date']))
        {
            $result = $result->where('created_at', '>=', $params['filter_start_date']);
        }

        if (!empty($params['filter_end_date']))
        {
            $result = $result->where('created_at', '<=', $params['filter_end_date']);
        }

        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Maintenance
     *
     * @param array $data
     *
     * @return MaintenanceRequest
     */
    public function createMaintenance(array $data): MaintenanceRequest
    {
        return $this->create($data);
    }

    /**
     * Find the Maintenance by id
     *
     * @param int $id
     *
     * @return MaintenanceRequest
     */
    public function findMaintenanceById(int $id): MaintenanceRequest
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Maintenance
     *
     * @param array $params
     *
     * @param MaintenanceRequest $maintenance
     * @return bool
     */
    public function updateMaintenance(array $data, MaintenanceRequest $maintenance): bool
    {
        return $this->update($data, $maintenance->id);
    }

    /**
     * @param MaintenanceRequest $maintenance
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMaintenance(MaintenanceRequest $maintenance)
    {
        return $this->delete($maintenance->id);
    }
}
