<?php

namespace App\Http\Controllers\Account;

use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Services\Auth\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;


class ChangePasswordController extends Controller
{
    /**
     * @var IUserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param IUserService $user
     */
    public function __construct(IUserService $user)
    {
        $this->userService = $user;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request)
    {
        if ($request->ajax())
        {
            return view('account.password');
        }
        return view('account.change-password');
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' =>  [
                'required','confirmed',
                Password::min(8) ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);


        $user = $this->userService->findUserById(user()->id);
        $data = $request->except('_token', '_method');
        $results = $this->userService->changePassword($data, $user);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', "Password changed successfully");

        return redirect()->back();
    }
}
