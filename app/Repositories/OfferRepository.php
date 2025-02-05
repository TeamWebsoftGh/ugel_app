<?php

namespace App\Repositories;

use App\Models\Offer;
use App\Repositories\Interfaces\IOfferRepository;
use Illuminate\Support\Collection;

class OfferRepository extends BaseRepository implements IOfferRepository
{
    /**
     * OfferRepository constructor.
     *
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct($offer);
        $this->model = $offer;
    }

    /**
     * Find the AdmissionPeriod by id
     *
     * @param int $id
     *
     * @return Offer
     */
    public function findOfferById(int $id): Offer
    {
        return $this->findOneOrFail($id);
    }


    /**
     * @param array $data
     *
     * @return Offer
     */
    public function createOffer(array $data) : Offer
    {
        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @param Offer $offer
     * @return bool
     */
    public function updateOffer(array $data, Offer $offer) : bool
    {
        return $this->update($data, $offer->id);
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    public function deleteOffer(Offer $offer) : bool
    {
        return $this->delete($offer->id);
    }

    /**
     *
     * @param array|null $params
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listOffers(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        $result = $this->getFilteredList($filter);

        return $result->orderBy($order, $sort)->get();
    }

}
