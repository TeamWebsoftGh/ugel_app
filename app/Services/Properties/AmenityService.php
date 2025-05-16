<?php

namespace App\Services\Properties;

use App\Models\Property\Amenity;
use App\Repositories\Property\Interfaces\IAmenityRepository;
use App\Services\Helpers\Response;
use App\Services\Properties\Interfaces\IAmenityService;
use App\Services\ServiceBase;
use Illuminate\Support\Collection;

class AmenityService extends ServiceBase implements IAmenityService
{
    private IAmenityRepository $amenityRepo;

    /**
     * AmenityService constructor.
     * @param IAmenityRepository $amenity
     */
    public function __construct(IAmenityRepository $amenity)
    {
        parent::__construct();
        $this->amenityRepo = $amenity;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sortBy
     *
     * @return Collection
     */
    public function listAmenities(array $filters =[], string $orderBy = 'id', string $sortBy = 'desc')
    {
        return $this->amenityRepo->listAmenities($filters, $orderBy, $sortBy);
    }


    /**
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function createAmenity(array $data): Response
    {
        $amenity = $this->amenityRepo->createAmenity($data);
        return $this->buildCreateResponse($amenity);
    }


    /**
     * @param array $data
     * @param Amenity $amenity
     * @return Response
     */
    public function updateAmenity(array $data, Amenity $amenity): Response
    {
        //Declaration
        $result = $this->amenityRepo->updateAmenity($data, $amenity);
        return $this->buildUpdateResponse($amenity, $result);
    }

    /**
     * @param int $id
     * @return Amenity|null
     */
    public function findAmenityById($id): ?Amenity
    {
        return $this->amenityRepo->findAmenityById($id);
    }

    /**
     * @param Amenity $amenity
     * @return Response
     */
    public function deleteAmenity(Amenity $amenity)
    {
        //Declaration
        $result = $this->amenityRepo->deleteAmenity($amenity);

        return $this->buildDeleteResponse($result);
    }

    public function deleteMultipleAmenities(array $ids)
    {
        //Declaration
        $result = $this->amenityRepo->deleteMultipleById($ids);

        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
