<?php

namespace App\Http\Controllers\CustomerService;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\CustomerService\Enquiry;
use App\Services\Interfaces\IEnquiryService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    private IEnquiryService $enquiryService;

    public function __construct(IEnquiryService $enquiryService)
    {
        $this->enquiryService = $enquiryService;
    }

    public function index()
    {
        $messages = $this->enquiryService->listEnquiryMessages();

        if (request()->ajax())
        {
            return datatables()->of($messages)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-visitor-logs'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-visitor-logs'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'document'])
                ->make(true);
        }

        return view('customer-service.enquiries.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|Response|View
     */
    public function edit(int $id)
    {
        $enquiry = $this->enquiryService->findEnquiryById($id);
        if (request()->ajax()){
            return view('customer-service.enquiries.edit', compact("enquiry"));
        }
        return redirect()->route("enquiries.index");
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required',
            'name' => 'required',
            'phone_number' => 'required',
            'message' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $asset = $this->enquiryService->findEnquiryById($request->input("id"));
            $results = $this->enquiryService->updateEnquiry($data, $asset);
        }else{
            $results = $this->enquiryService->createEnquiry($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('enquiries.index');
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
