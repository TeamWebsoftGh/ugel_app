<?php

namespace App\Services;

use App\Models\Audit\LogActivity;
use App\Repositories\Interfaces\IAuditRepository;
use App\Services\Interfaces\IAuditService;
use Illuminate\Support\Collection as Support;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class AuditService extends ServiceBase implements IAuditService
{
    private  $auditRepo;

    /**
     * AuditService constructor.
     * @param IAuditRepository $auditRepository
     */
    public function __construct(IAuditRepository $auditRepository)
    {
        $this->auditRepo = $auditRepository;
    }

    /**
     * Get the authenticated User
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Support [json] user object
     */
    public function listLogs(string $order = 'id', string $sort = 'desc', array $columns = ['*']):Support
    {
        return $this->auditRepo->listLogs();
    }


    /**
     * Create the customer
     *
     * @param $type
     * @return Support
     */
    public function listLogsByType($type) : Support
    {
        return $this->auditRepo->listLogsByType($type);
    }

    /**
     * Update the customer
     *
     * @param array $params
     *
     * @return bool
     */
    public function deleteLog(int $params) : LogActivity
    {
        try{
            return $this->model->update($params);
        }catch (QueryException $e){
            throw new QueryException($e->getMessage());
        }
        return $this->model->update($params);
    }

    /**
     * Find the customer or fail
     *
     * @param int $id
     *
     * @return Customer
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
    public function searchLog(string $text) : Collection
    {
        return $this->model->searchCustomer($text, ['name' => 10, 'email' => 5])->get();
    }

    public function createLog(array $params): LogActivity
    {
        // TODO: Implement createLog() method.
        return new LogActivity();
    }
}
