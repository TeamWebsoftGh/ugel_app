<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\LoanDetails\PersonalDetail;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IClientService;
use App\Services\Interfaces\ICustomerService;
use App\Traits\CustomerTransformable;
use App\Traits\JsonResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisteredUserController extends MobileController
{
    use JsonResponseTrait, CustomerTransformable;

    private IClientService $clientService;

    /**
     * Create a new controller instance.
     *
     * @param IClientService $client
     */
    public function __construct(IClientService $client)
    {
        parent::__construct();
        $this->clientService = $client;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'first_name' => ['required'],
            'sector_code' => ['required'],
            'last_name' => ['required'],
            'client_type' => 'required',
            'phone_number' => ['required', 'string', 'max:14', 'unique:clients'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $results = $this->clientService->createClient([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'customer_id' => $request->customer_id,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'client_type' => $request->client_type,
            'sector_code' => $request->sector_code,
        ]);

        if ($results->status == ResponseType::SUCCESS)
        {
            $user = $results->data??null;
            event(new Registered($user));
            $results->data = [
                'token' => $user->createToken('ApiToken')->plainTextToken,
                'user' => $this->transformClient($user)
            ];
        }

        return $this->apiResponseJson($results);
    }

    public function sendCode(Request $request)
    {
        $results = $this->clientService->sendOtp($request->user());
        return $this->apiResponseJson($results);
    }

    public function clientResgister(Request $request)
    {
        $results = $this->clientService->getCreateClient();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $results);
    }

    public function verifyCode(Request $request)
    {
        $validatedData = $request->validate([
            'otp' => 'required',
        ]);

        $results = $this->clientService->verifyOtp($request->user(), $request->otp);
        if ($results?->status==ResponseType::SUCCESS) {
            $client = $request->user();
            $client->phone_verified_at  = now();
            $client->save();
        }

        return $this->apiResponseJson($results);
    }

    public function verifyGhanaCard(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required',
            'pin' => 'required',
        ]);

        $results = $this->clientService->verifyGhanaCard($request->user(), $request->all());
        return $this->apiResponseJson($results);
    }
}
