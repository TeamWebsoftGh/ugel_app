<?php

namespace App\Services\Properties;

use App\Models\Property\Review;
use App\Repositories\Property\Interfaces\IReviewRepository;
use App\Services\Properties\Interfaces\IReviewService;
use App\Services\ServiceBase;

class ReviewService extends ServiceBase implements IReviewService
{
    private IReviewRepository $reviewRepo;

    /**
     * ReviewService constructor.
     *
     * @param IReviewRepository $review
     */
    public function __construct(IReviewRepository $review){
        parent::__construct();
        $this->reviewRepo = $review;
    }

    public function listReviews(array $filter = [], string $orderBy = 'updated_at', string $sortBy = 'desc')
    {
        return $this->reviewRepo->listReviews($filter, $orderBy, $sortBy);
    }

    public function createReview(array $params)
    {
        //Declaration
        $review = $this->reviewRepo->create($params);
        return $this->buildCreateResponse($review);
    }

    public function findReviewById(int $id): Review
    {
        return $this->reviewRepo->findOneOrFail($id);
    }

    public function updateReview(array $params, Review $review)
    {
        //Declaration
        $result = $this->reviewRepo->update($params, $review->id);
        return $this->buildUpdateResponse($review, $result);
    }

    public function deleteReview(Review $review)
    {
        //Declaration
        $result = $this->reviewRepo->delete($review->id);
        return $this->buildDeleteResponse($result);
    }

    public function deleteMultiplePropertyTypes(array $ids)
    {
        //Declaration
        $result = $this->reviewRepo->deleteMultipleById($ids);
        return $this->buildDeleteResponse($result, "Records deleted successfully.");
    }
}
