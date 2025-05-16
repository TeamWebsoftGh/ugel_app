<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Services\Auth\Interfaces\IUserService;
use App\Services\Interfaces\IClientService;
use App\Traits\CustomerTransformable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends MobileController
{
    use CustomerTransformable;

    private IClientService $clientService;
    private IUserService $userService;

    /**
     * Create a new controller instance.
     *
     * @param IClientService $client
     */
    public function __construct(IClientService $client, IUserService $userService)
    {
        parent::__construct();
        $this->clientService = $client;
        $this->userService = $userService;
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
            'title' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'unique:users', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'], // Slug format (lowercase, no spaces, hyphen-separated)
            'client_type_id' => ['required', 'exists:client_types,id'], // Ensure client_type_id exists in client_types table
            'phone_number' => ['required', 'string', 'max:14', 'regex:/^[0-9\-\+\(\)]+$/'], // Allow numbers, dashes, plus, and parentheses
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::min(8)->uncompromised()->mixedCase()->numbers()->symbols()], // Strong password requirement
        ]);


        $results = $this->clientService->createClient([
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => $request->password,
            'client_type_id' => $request->client_type_id,
        ]);

        if ($results->status == ResponseType::SUCCESS)
        {
            $client = $results->data??null;
            $user = $client->users()->first();
            event(new Registered($user));
            $results->data = [
                'token' => $user?->createToken('ApiToken')->plainTextToken,
                'user' => new UserResource($user)
            ];
        }

        return $this->apiResponseJson($results);
    }

    public function sendCode(Request $request)
    {
        $results = $this->userService->sendOtp($request->user());
        return $this->apiResponseJson($results);
    }

    public function register(Request $request)
    {
        $results = $this->clientService->getCreateClient();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $results);
    }

    public function verifyCode(Request $request)
    {
        $validatedData = $request->validate([
            'otp' => 'required',
        ]);

        $results = $this->userService->verifyOtp($request->user(), $request->otp);
        if ($results?->status==ResponseType::SUCCESS && !isset($request->user()?->phone_verified_at)) {
            $user = $request->user();
            $user->phone_verified_at  = now();
            $user->save();
        }

        return $this->apiResponseJson($results);
    }
}
