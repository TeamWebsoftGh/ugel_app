<?php

namespace App\Repositories\Interfaces;

use App\Models\Property\Amenity;

interface IAmenityRepository extends IBaseRepository
{
    public function updateAmenity(array $data, Amenity $amenity);

    public function listAmenities(array $filter = [], string $order = 'id', string $sort = 'desc');

    public function createAmenity(array $data) : Amenity;

    public function findAmenityById(int $id) : Amenity;

    public function deleteAmenity(Amenity $amenity);

}
