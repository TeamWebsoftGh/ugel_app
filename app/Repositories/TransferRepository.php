<?php

namespace App\Repositories;

use App\Models\Property\Transfer;
use App\Repositories\Interfaces\ITransferRepository;
use Illuminate\Support\Collection;

class TransferRepository extends BaseRepository implements ITransferRepository
{
    /**
     * SemesterRepository constructor.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {
        parent::__construct($transfer);
        $this->model = $transfer;
    }

    /**
     * Find the Semester by id
     *
     * @param int $id
     *
     * @return Transfer
     */
    public function findTransferById(int $id): Transfer
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Transfer
     */
    public function createTransfer(array $data) : Transfer
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Transfer $transfer
     * @return bool
     */
    public function updateTransfer(array $data, Transfer $transfer) : bool
    {
        return $transfer->update($data);
    }

    /**
     * @param Transfer $transfer
     * @return bool
     */
    public function deleteTransfer(Transfer $transfer) : bool
    {
        return $transfer->delete();
    }

    /**
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listTransfers(array $filter, string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }

}
