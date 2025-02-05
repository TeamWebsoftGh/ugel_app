<?php

namespace App\Repositories\Interfaces;

use App\Models\Review;
use Illuminate\Support\Collection;

interface IReviewRepository extends IBaseRepository
{
    public function listReviews(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function findReviewById(int $id) : Review;

    public function updateReview(array $params, Review $review) : bool;

    public function createReview(array $params) : Review;
}
