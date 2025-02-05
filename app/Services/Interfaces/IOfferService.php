<?php

namespace App\Services\Interfaces;

use App\Models\Offer;
use Illuminate\Support\Collection;

interface IOfferService extends IBaseService
{
    public function listOffers(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection;

    public function createOffer(array $data);

    public function updateOffer(array $data, Offer $offer);

    public function findOfferById(int $id);

    public function deleteOffer(Offer $offer);
}
