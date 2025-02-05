<?php

namespace App\Repositories;

use App\Models\Property\Amenity;
use App\Repositories\Interfaces\IAmenityRepository;

class AmenityRepository extends BaseRepository implements IAmenityRepository
{
    /**
     * AmenityRepository constructor.
     *
     * @param Amenity $amenity
     */
    public function __construct(Amenity $amenity)
    {
        parent::__construct($amenity);
        $this->model = $amenity;
    }

    /**
     * List all the Amenities
     *
     * @param string $order
     * @param string $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder $amenities
     */
    public function listAmenities(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $result = $this->getFilteredList();
        return $result->orderBy($order, $sort);
    }

    /**
     * Create the Amenity
     *
     * @param array $data
     *
     * @return Amenity
     */
    public function createAmenity(array $data): Amenity
    {
        return $this->create($data);
    }

    /**
     * Find the Amenity by id
     *
     * @param int $id
     *
     * @return Amenity
     */
    public function findAmenityById(int $id): Amenity
    {
        return $this->findOneOrFail($id);
    }

    /**
     * Update Amenity
     *
     * @param array $params
     *
     * @param Amenity $amenity
     * @return bool
     */
    public function updateAmenity(array $data, Amenity $amenity): bool
    {
        return $this->update($data, $amenity->id);
    }

    /**
     * @param Amenity $amenity
     * @return bool|null
     * @throws \Exception
     */
    public function deleteAmenity(Amenity $amenity)
    {
        return $this->delete($amenity->id);
    }
}
