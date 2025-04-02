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
     * @return \Illuminate\Database\Eloquent\Builder $maintenances
     */
    public function listMaintenances(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        $query = MaintenanceRequest::query();

        // Restrict based on permissions if the user doesn't have the "read-maintenance" ability
        if (!user()->can('read-maintenance')) {
            $query->where(function ($query) {
                $query->whereHas('assignees', function ($q) {
                    $q->where('user_id', user()->id);
                })->orWhere('user_id', user()->id);
            });
        }
//
//        $query->when(!empty($filter['filter_subsidiary']), function ($q) use ($filter) {
//            $q->whereHas('user', function ($query) use ($filter) {
//                $query->where('subsidiary_id', $filter['filter_subsidiary']);
//            });
//        });

        $query->when(!empty($filter['filter_status']), function ($q) use ($filter) {
            $q->where('status_id', $filter['filter_status']);
        });

        $query->when(!empty($filter['filter_assignee']), function ($q) use ($filter) {
            $q->whereHas('assignees', function ($query) use ($filter) {
                $query->where('id', $filter['filter_assignee']);
            });
        });

        $query->when(!empty($filter['filter_user']), function ($q) use ($filter) {
            $q->where('user_id', $filter['filter_user']);
        });

        $query->when(!empty($filter['filter_property']), function ($q) use ($filter) {
            $q->where('property_id', $filter['filter_property']);
        });

        $query->when(!empty($filter['filter_customer']), function ($q) use ($filter) {
            $q->where('client_id', $filter['filter_customer']);
        });

        $query->when(!empty($filter['filter_priority']), function ($q) use ($filter) {
            $q->where('priority_id', $filter['filter_priority']);
        });

        $query->when(!empty($filter['filter_category']), function ($q) use ($filter) {
            $q->where('maintenance_category_id', $filter['filter_category']);
        });

        $query->when(!empty($filter['filter_start_date']), function ($q) use ($filter) {
            $q->whereDate('created_at', '>=', $filter['filter_start_date']);
        });

        $query->when(!empty($filter['filter_end_date']), function ($q) use ($filter) {
            $q->whereDate('created_at', '<=', $filter['filter_end_date']);
        });

        return $query->orderBy($order, $sort);
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
