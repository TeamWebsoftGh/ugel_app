<?php

namespace App\Services\Properties\Interfaces;

use App\Models\Property\Review;
use App\Services\Interfaces\IBaseService;

interface IReviewService extends IBaseService
{
    public function listReviews(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc');

    public function createReview(array $params);

    public function findReviewById(int $id) : Review;

    public function updateReview(array $params, Review $review);
    public function deleteReview(Review $review);

    public function deleteMultiplePropertyTypes(array $ids);
}
