<?php

namespace App\Http\Controllers\Api\Mobile\Account;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseType;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AccountController extends MobileController
{
    /**
     */
    private IUserService $userService;

    /**
     * AccountController constructor.
     *
     * @param IUserService $client
     */
    public function __construct(IUserService $client)
    {
        parent::__construct();
        $this->userService = $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $this->userService->findUserById($request->user()->id);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = "";
        $this->response->data = new UserResource($user);

        return $this->apiResponseJson($this->response);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $this->userService->findUserById(user()->id);

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'phone_number' => 'nullable|phone',
        ]);

        $data = $request->except('_token', '_method');

        $results = $this->userService->updateUser($data, $user);

        if($results->status == ResponseType::SUCCESS)
        {
            $results->data = new UserResource($results->data);
        }

        return $this->apiResponseJson($results);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' =>  [
                'required',
                Password::min(8) ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        $user = $this->userService->findUserById($request->user()->id);
        $data = $request->except('_token', '_method');
        $results = $this->userService->changePassword($data, $user);

        return $this->apiResponseJson($results);
    }
}
