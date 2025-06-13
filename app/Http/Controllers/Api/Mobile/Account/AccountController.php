<?php

namespace App\Http\Controllers\Api\Mobile\Account;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Resources\UserResource;
use App\Services\Auth\Interfaces\IUserService;
use Carbon\Carbon;
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
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'sometimes|unique:users,email,'.$user->id,
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

    public function notifications(Request $request)
    {
        $user =user();
        $rows = $user->unreadNotifications;
        $notifications = [];
        foreach ($rows->slice(0,12) as $k => $notification) {
            $notifications[] = [
                'id' => $notification->id,
                'created_at' => $notification->created_at,
                'created_at_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                'type' => $notification->type,
                'message' => $notification->data['message'],
                'read_at' => $notification->read_at,
                'url' => $notification->data['url']??"/",
                'icon' => $notification->data['icon']??'',
                'title' => $notification->data['title']??'',
                'user_id' => $user->id
            ];
        }

        // dd( $user->unreadNotifications, get_current_user());
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $notifications);

    }

    public function clearNotifications()
    {
        user()->notifications()->delete();
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS);
    }
}
