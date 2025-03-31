<?php

namespace App\Http\Controllers\Account;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Services\Auth\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    /**
     */
    private IUserService $userService;

    /**
     * AccountController constructor.
     *
     * @param IUserService $user
     */
    public function __construct(IUserService $user)
    {
        $this->userService = $user;
    }
    /**
     * Display a user profile
     *
     * @return Response
     */
    public function index()
    {
        $user = user();
        return view('account.profile', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $user = user();
        return view('account.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $user = $this->userService->findUserById(user()->id);

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'username' => 'sometimes|unique:users,username,'.$user->id,
            'phone_number' => 'required|phone|unique:users,phone_number,'.$user->id,
        ]);

        $data = $request->except('_token', '_method');

        $results = $this->userService->updateUser($data, $user);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', "Profile updated successfully");

        return redirect()->back();
    }
}
