<?php

namespace App\Repositories;

use App\Models\Property\Travel;
use App\Repositories\Interfaces\ITravelRepository;
use Illuminate\Support\Collection;

class TravelRepository extends BaseRepository implements ITravelRepository
{
    /**
     * TravelRepository constructor.
     *
     * @param Travel $travel
     */
    public function __construct(Travel $travel)
    {
        parent::__construct($travel);
        $this->model = $travel;
    }

    /**
     * List all the Travels
     *
     * @param string $order
     * @param string $sort
     *
     * @param array $columns
     * @return Collection $travels
     */
    public function listTravels(array $params = null, string $order = 'updated_at', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        $result = $this->getFilteredList($params);

        return $result->orderBy($order, $sort)->get($columns);
    }


    /**
     * Add a Travel
     *
     * @param array $data
     *
     * @return Travel
     * @throws \Exception
     */
    public function createTravel(array $data): Travel
    {
        return $this->create($data);
    }


    /**
     * Find the Travel by id
     *
     * @param int $id
     *
     * @return Travel
     */
    public function findTravelById(int $id): Travel
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Travel
     *
     * @param array $data
     * @param Travel $travel
     * @return bool
     */
    public function updateTravel(array $data, Travel $travel): bool
    {
        return $this->update($data, $travel->id);
    }

    /**
     * @param Travel $travel
     * @return bool|null
     * @throws \Exception
     */
    public function deleteTravel(Travel $travel)
    {
        return $this->delete($travel->id);
    }
}
