<?php

namespace App\Services\Interfaces\Properties;


use App\Models\Property\Amenity;
use App\Services\Interfaces\IBaseService;

interface IAmenityService extends IBaseService
{
    public function listAmenities(array $filters = []);

    public function createAmenity(array $data);

    public function findAmenityById($id);

    public function updateAmenity(array $data, Amenity $amenity);

    public function deleteAmenity(Amenity $amenity);

    public function deleteMultipleAmenities(array $ids);
}
