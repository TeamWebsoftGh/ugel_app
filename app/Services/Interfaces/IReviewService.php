<?php

namespace App\Services\Interfaces;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Collection;

interface IReviewService extends IBaseService
{
    public function listReviews(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function createReview(array $params, Order $order);

    public function findReviewById(int $id) : Review;

    public function findReviewByWriter(int $id);

    public function updateReview(array $params, Review $review);
}
