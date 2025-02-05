<?php

namespace App\Repositories;

use App\Models\IncomeTax;
use App\Repositories\Interfaces\IIncomeTaxRepository;
use Illuminate\Support\Collection;

class IncomeTaxRepository extends BaseRepository implements IIncomeTaxRepository
{
    /**
     * IncomeTaxRepository constructor.
     * @param IncomeTax $incomeTax
     */
    public function __construct(IncomeTax $incomeTax)
    {
        parent::__construct($incomeTax);
        $this->model = $incomeTax;
    }


    /**
     * List all IncomeTaxs
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection
     */
    public function listIncomeTaxes(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create the appUser
     *
     * @param array $data
     *
     * @return IncomeTax
     */
    public function createIncomeTax(array $data): IncomeTax
    {
        return $this->create($data);
    }


    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return IncomeTax
     */
    public function findIncomeTaxById(int $id): IncomeTax
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update ApplicationUser
     *
     * @param array $params
     * @param IncomeTax $incomeTax
     * @return bool
     */
    public function updateIncomeTax(array $params, IncomeTax $incomeTax): bool
    {
        return $this->update($params, $incomeTax->id);
    }


    /**
     * @param IncomeTax $incomeTax
     * @return bool
     */
    public function deleteIncomeTax(IncomeTax $incomeTax)
    {
        return $this->delete($incomeTax->id);
    }
}
