<?php

namespace App\Repositories\Interfaces;

use App\Models\Offer;
use Illuminate\Support\Collection;

interface IOfferRepository extends IBaseRepository
{
    public function findOfferById(int $id);

    public function listOffers(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createOffer(array $data);

    public function updateOffer(array $data, Offer $offer);

    public function deleteOffer(Offer $offer);
}
