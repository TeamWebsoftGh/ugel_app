<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Order;
use App\Models\Review;
use App\Repositories\Interfaces\IReviewRepository;
use App\Services\Interfaces\IReviewService;
use Illuminate\Support\Collection;

class ReviewService extends ServiceBase implements IReviewService
{
    private $reviewRepo;

    /**
     * ReviewService constructor.
     *
     * @param IReviewRepository $review
     */
    public function __construct(IReviewRepository $review){
        parent::__construct();
        $this->reviewRepo = $review;
    }

    public function listReviews(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->reviewRepo->listReviews($order, $sort, $columns);
    }

    public function createReview(array $params, Order $order)
    {
        //Declaration
        $review = null;

        //Process Request
        try {

            $params["order_id"] = $order->id;
            $params["writer_id"] = $order->writer_id;
            $customer = $this->reviewRepo->createReview($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new Review(), 'create-review-failed');
        }

        //Check if review was created successfully
        if (!$review || $review == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-review-successful';
        $auditMessage = 'You have successfully added a new Review';

        log_activity($auditMessage, $review, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $review;

        return $this->response;
    }

    public function findReviewById(int $id): Review
    {
        return $this->reviewRepo->findReviewById($id);
    }

    public function updateReview(array $params, Review $review)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->reviewRepo->updateReview($params, $review);
        } catch (\Exception $e) {
            log_error(format_exception($e), $review, 'update-review-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-review-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $review, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $review;

        return $this->response;
    }

    public function findReviewByWriter(int $id)
    {
        // TODO: Implement findReviewByWriter() method.
    }
}
