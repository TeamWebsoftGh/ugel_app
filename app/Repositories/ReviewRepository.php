<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\IReviewRepository;
use Illuminate\Support\Collection;

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

    /**
     * List all the Regions
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listReviews(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $order, $sort);
    }


    /**
     * List all the Regions
     *
     * @param array $params
     * @return bool
     */
    public function createReview(array $params) : Review
    {
        return $this->create($params);
    }

    /**
     * Find the Region
     *
     * @param int $id
     * @return Review
     */
    public function findReviewById(int $id) : Review
    {
        return $this->findOneOrFail($id);
    }


    /**
     * Update the Region
     *
     * @param array $params
     * @param Review $review
     * @return boolean
     */
    public function updateReview(array $params, Review $review) : bool
    {
        return $this->update($params, $review->id);
    }
}
