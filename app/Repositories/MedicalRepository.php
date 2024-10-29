<?php

namespace App\Repositories;

use App\Models\Property\Medical;
use App\Repositories\Interfaces\IMedicalRepository;
use Illuminate\Support\Collection;

class MedicalRepository extends BaseRepository implements IMedicalRepository
{
    /**
     * MedicalRepository constructor.
     *
     * @param Medical $medical
     */
    public function __construct(Medical $medical)
    {
        parent::__construct($medical);
        $this->model = $medical;
    }

    /**
     * List all the Medicals
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection $payments
     */
    public function listMedicals(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($params);

        return $result->orderBy($order, $sort)->get($columns);
    }


    /**
     * Add a Medical
     *
     * @param array $data
     *
     * @return Medical
     * @throws \Exception
     */
    public function createMedical(array $data): Medical
    {
        return $this->create($data);
    }


    /**
     * Find the Medical by id
     *
     * @param int $id
     *
     * @return Medical
     */
    public function findMedicalById(int $id): Medical
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Medical
     *
     * @param array $data
     * @param Medical $medical
     * @return bool
     */
    public function updateMedical(array $data, Medical $medical): bool
    {
        return $this->update($data, $medical->id);
    }

    /**
     * @param Medical $medical
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMedical(Medical $medical)
    {
        return $this->delete($medical->id);
    }
}
