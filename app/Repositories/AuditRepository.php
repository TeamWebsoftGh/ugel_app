<?php

namespace App\Repositories;

use App\Models\Audit\LogActivity;
use App\Repositories\Interfaces\IAuditRepository;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class AuditRepository extends BaseRepository implements IAuditRepository
{
    /**
     * CustomerRepository constructor.
     * @param LogActivity $log
     */
    public function __construct(LogActivity $log)
    {
        parent::__construct($log);
        $this->model = $log;
    }

    /**
     * Get the authenticated User
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection [json] user object
     */
    public function listLogs(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }


    public function createLog(array $params): LogActivity
    {
        // TODO: Implement createLog() method.
    }


    /**
     *
     * @param  $params
     * @return Collection
     */
    public function listLogsByType($params) : Support
    {
        return $this->listLogs()->where('log_type_id', '==', $params);
    }

    /**
     * Update the customer
     *
     * @param array $params
     *
     * @return bool
     */
    public function deleteLog(int $id)
    {
        return $this->model->delete($id);
    }

    /**
     * Find the customer or fail
     *
     * @param int $id
     *
     * @return LogActivity
     */
    public function findLogById(int $id) : LogActivity
    {
        try{
            return $this->findOneOrFail($id);
        }catch (ModelNotFoundException $e){
            throw new ModelNotFoundException($e->getMessage());
        }
    }

    /**
     * @param string $text
     * @return mixed
     */
    public function searchLog(string $text)
    {
        return $this->model->searchCustomer($text, ['name' => 10, 'email' => 5])->get();
    }

}
