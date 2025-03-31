<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\ReviewResource;
use App\Services\Properties\Interfaces\IReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class ReviewController extends MobileController
{
    /**
     * @var ReviewController
     */
    private IReviewService $reviewService;

    /**
     * CategoryController constructor.
     *
     * @param IReviewService $reviewService
     */
    public function __construct(IReviewService $reviewService)
    {
        parent::__construct();
        $this->reviewService = $reviewService;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['filter_client'] = user()->client_id;
        // Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);
        $query = $this->reviewService->listReviews($data);

        if ($perPage < 0) {
            // Apply pagination if enabled
            $items = $query->paginate($perPage, ['*'], 'page', $page);
        } else {
            // Return all records if pagination is disabled
            $items = $query->get();
        }

        $item = ReviewResource::collection($items);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    public function show(Request $request, $id)
    {
        $property = $this->reviewService->findReviewById($id);
        $item = new ReviewResource($property);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'rating' => 'required',
            'comment' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['client_id'] = user()->client_id;
        $results = $this->reviewService->createReview($data);

        if(isset($results->data))
        {
            $results->data = new ReviewResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'review_id' => 'required',
            'rating' => 'required',
            'comment' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id', 'client_id');
        $item = $this->reviewService->findReviewById($data['review_id']);

        $results = $this->reviewService->updateReview($data, $item);

        if(isset($results->data))
        {
            $results->data = new ReviewResource($results->data);
        }

        return $this->apiResponseJson($results);
    }


    public function destroy(int $id)
    {
        $booking = $this->reviewService->findReviewById($id);
        $results = $this->reviewService->deleteReview($booking);

        return $this->apiResponseJson($results);
    }
}
