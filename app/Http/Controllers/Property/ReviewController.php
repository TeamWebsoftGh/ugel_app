<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Property\Property;
use App\Models\Property\PropertyCategory;
use App\Models\Property\Review;
use App\Services\Properties\Interfaces\IReviewService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private IReviewService $reviewService;

    /**
     * Create a new controller instance.
     *
     * @param IReviewService $review
     */
    public function __construct(IReviewService $review)
    {
        parent::__construct();
        $this->reviewService = $review;
    }


    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        if (request()->ajax())
        {
            $data = request()->all();
            $types = $this->reviewService->listReviews($data);

            return datatables()->of($types)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('property_name', function ($row)
                {
                    return $row->property->property_name ?? '';
                })
                ->addColumn('client_name', function ($row)
                {
                    return $row->client->fullname ?? '';
                })
                ->addColumn('client_number', function ($row)
                {
                    return $row->client->client_number ?? '';
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->addColumn('action', fn($data) => $this->getActionButtons($data, "booking-periods"))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('property.reviews.index');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View
     */
    public function create()
    {
        $item = new Review();
        $properties = Property::select('id', 'property_name')->get();

        if (request()->ajax()){
            return view('property.reviews.edit', compact('item', 'properties'));
        }

        return redirect()->route("reviews.index");
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
     */
	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'rating' => 'required',
            'property_id' => 'required',
            'client_id' => 'required',
            'comment' => 'required',
            'is_active' => 'sometimes',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $property_type = $this->reviewService->findReviewById($request->input("id"));
            $results = $this->reviewService->updateReview($data, $property_type);
        }else{
            $results = $this->reviewService->createReview($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('reviews.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param int $id
     * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function show($id)
	{
        $property_type = $this->reviewService->findReviewById($id);
        $categories = PropertyCategory::select('id', 'name')->get();

        if (request()->ajax()){
            return view('property.reviews.edit', compact('property_type', 'categories'));
        }

        return redirect()->route("reviews.index");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Factory|\Illuminate\Foundation\Application|View|\Illuminate\Http\RedirectResponse
     */
	public function edit($id)
	{
        $item = $this->reviewService->findReviewById($id);
        $properties = Property::select('id', 'property_name')->get();

        if (request()->ajax()){
            return view('property.reviews.edit', compact('item', 'properties'));
        }

        return redirect()->route("reviews.index");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
     */
	public function destroy(int $id)
	{
        $award = $this->reviewService->findReviewById($id);
        $result = $this->reviewService->deleteReview($award);

        return $this->responseJson($result);
	}

    /**
     * Bulk delete resources from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $result = $this->reviewService->deleteMultiplePropertyTypes($request->ids);
        return $this->responseJson($result);
    }
}
