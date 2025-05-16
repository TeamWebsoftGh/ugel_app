<?php

namespace App\Repositories\Property;

use App\Models\Property\Review;
use App\Repositories\BaseRepository;
use App\Repositories\Property\Interfaces\IReviewRepository;

class ReviewRepository extends BaseRepository implements IReviewRepository
{
    /**
     * ReviewRepository constructor.
     * @param Review $review
     */
    public function __construct(Review $review)
    {
        parent::__construct($review);
        $this->model = $review;
    }

    public function listReviews(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): \Illuminate\Database\Eloquent\Builder
    {
        $query = $this->getFilteredList();
        $query->when(!empty($filter['filter_property_unit']), function ($q) use ($filter) {
            $q->where('property_unit_id', $filter['filter_property_unit']);
        });
        $query->when(!empty($filter['filter_property']), function ($q) use ($filter) {
            $q->where('property_id', $filter['filter_property']);
        });
        $query->when(!empty($filter['filter_client']), function ($q) use ($filter) {
            $q->where('client_id', $filter['filter_client']);
        });
        return $query->orderBy($order, $sort);
    }
}
