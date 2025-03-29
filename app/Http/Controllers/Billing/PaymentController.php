<?php

namespace App\Http\Controllers\Property;

use App\Abstracts\Http\Controller;
use App\Constants\Constants;
use App\Constants\ResponseType;
use App\Models\Common\TravelType;
use App\Models\Property\Complaint;
use App\Models\Property\Medical;
use App\Models\Property\Travel;
use App\Services\Interfaces\IMedicalService;
use App\Services\Interfaces\IPaymentService;
use App\Services\Interfaces\ITravelService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private IPaymentService $paymentService; // Update the service variable name

    /**
     * Create a new controller instance.
     *
     * @param IMedicalService $medicalService
     */
    public function __construct(IPaymentService $paymentService) // Update the constructor parameter
    {

        $this->paymentService = $paymentService; // Update the service assignment
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();

        return view('property.payments.index');
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create() // Update the method name
    {
        $medical = new Medical();
        $blood_groups = Constants::BLOOD_GROUPS;

        $fillableFields = $this->getFillable();

        if (request()->ajax()){
            return view('property.payments.edit', compact('medical', 'blood_groups', 'fillableFields'));
        }

        return view('property.payments.create', compact('medical', 'blood_groups', 'fillableFields'));
    }


    /**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
     */
	public function store(Request $request)
	{
        $validatedData = $request->validate([
            'employee_id' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'hospital_name' => 'required',
            'exam_date' => 'required',
            'fit_to_work' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');
        $data['travel_type'] = $request->arrangement_type;

        if ($request->has("id") && $request->input("id") != null)
        {
            $travel = $this->medicalService->findMedicalById($request->input("id"));
            $results = $this->medicalService->updateMedical($data, $travel);
        }else{
            $results = $this->medicalService->createMedical($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('property.payments.index');
	}


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function show($id)
    {
        $medical = $this->medicalService->findMedicalById($id);
        $blood_groups = Constants::BLOOD_GROUPS;
        $fillableFields = $this->getFillable();

        if (request()->ajax()){
            return view('property.payments.edit', compact('medical', 'blood_groups', 'fillableFields'));
        }

        return redirect()->route("property.payments.index");
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
	public function edit($id)
	{
        $medical = $this->medicalService->findMedicalById($id);
        $blood_groups = Constants::BLOOD_GROUPS;

        $fillableFields = $this->getFillable();

        if (request()->ajax()){
            return view('property.payments.edit', compact('medical', 'blood_groups', 'fillableFields'));
        }

        return redirect()->route("property.payments.index");
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $medical = $this->medicalService->findMedicalById($id); // Update the method call

        $result = $this->medicalService->deleteMedical($medical); // Update the method call

        return $this->responseJson($result);
    }

    public function bulkDelete(Request $request)
    {
        $logged_user = auth()->user();

        if ($logged_user->can('delete-payments'))
        {
            $medical_id = $request['medicalIdArray'];
            $medical = Medical::whereIn('id', $medical_id);
            if ($medical->delete())
            {
                return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Medical')])]); // Update the message
            } else
            {
                return response()->json(['error' => 'Error, selected medical records can not be deleted']); // Update the message
            }
        }
        return response()->json(['success' => __('You are not authorized')]); // Update the message
    }

    private function getFillable(){
        return [
            'hospital_name',
            'doctor_name',
            'sickling',
            'height',
            'weight',
            'mouth_and_teeth',
            'cvs',
            'resp_system',
            'abdomen',
            'mss_skin',
            'vision',
            'immunization_record',
            'hearing',
            'chest_xray',
            'other_tests',
            'hepatitis',
        ];
    }
}
