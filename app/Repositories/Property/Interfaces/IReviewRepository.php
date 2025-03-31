<?php

namespace App\Repositories\Property\Interfaces;

use App\Repositories\Interfaces\IBaseRepository;

interface IReviewRepository extends IBaseRepository
{
    public function listReviews(array $filter = [], string $order = 'updated_at', string $sort = 'desc');
}
