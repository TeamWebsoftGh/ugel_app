<?php

namespace App\Http\Controllers\Api\Mobile\CustomerService;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Resources\EnquiryResource;
use App\Models\CustomerService\Enquiry;
use App\Services\Interfaces\IEnquiryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EnquiryController extends MobileController
{
    private IEnquiryService $enquiryService;

    public function __construct(IEnquiryService $enquiryService)
    {
        parent::__construct();
        $this->enquiryService = $enquiryService;
    }

    public function index(Request $request)
    {
        // Get all request data
        $data = $request->all();
        $data['client_id'] = user()->client_id;

        // Get the collection of polling stations
        $items = $this->enquiryService->listEnquiryMessages($data);

        // Manually paginate the collection
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $items->paginate($perPage);

        // Transform the items using a resource collection
        $items = EnquiryResource::collection($paginatedItems);

        // Return the paginated response
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items, $paginatedItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'form_id' => 'required|unique:customer_enquiries,form_id,',
            'subject' => 'required',
            'phone_number' => 'required',
            'message' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $data = $request->all();
        $data['is_active']= 1;
        $data['client_id'] = user()->client_id;

        $results = $this->enquiryService->createEnquiry($data);

        if(isset($results->data))
        {
            $results->data = new EnquiryResource($results->data);
        }

        return $this->apiResponseJson($results);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $enquiry = new Enquiry();
        if (request()->ajax()){
            return view('customer-service.enquiries.edit', compact("enquiry"));
        }

        return redirect()->route("enquiries.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $enquiry = $this->enquiryService->findEnquiryById($id);
        $result = $this->enquiryService->deleteEnquiry($enquiry);

        return $this->responseJson($result);
    }
}
