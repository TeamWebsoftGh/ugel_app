<?php

namespace App\Http\Controllers\User;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Services\Interfaces\IUserService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    use JsonResponseTrait;

    private IUserService $userService;

    /**
     * Create a new controller instance.
     *
     * @param IUserService $user
     */
    public function __construct(IUserService $user)
    {
        parent::__construct();
        $this->middleware(['permission:update-users'], ['only' => ['changeStatus', 'resetPassword']]);

        $this->userService = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->userService->getCreateUser($request->all());
        if (request()->ajax())
        {
            return datatables()->of($data['users'])
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('name', function ($row)
                {
                    return $row->fullname;
                })
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->make(true);
        }

        return view('user-access.users.create', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function create(Request $request)
    {
        $data = $this->userService->getCreateUser($request->all());

        return view('users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,'.$request->input("id"),
            'email' => 'required|unique:users,email,'.$request->input("id"),
            'phone_number' => 'nullable|phone',
            'department_id' => 'required',
            'date_of_birth' => 'nullable',
            'role' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $user = $this->userService->findUserById($request->input("id"));
            $results = $this->userService->updateUser($data, $user);
        }else{
            $results = $this->userService->createUser($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = $this->userService->getCreateUser($request->all());
        $data['user'] = $this->userService->findUserById($id);
        $data['roleId'] = optional($data['user']->roles()->first())->id;

        if ($request->ajax()){
            return view('user-access.users.edit', $data);
        }

        return redirect()->route("users.create", ["userId" => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = $this->userService->findUserById($id);

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'username' => 'sometimes|unique:users,username,'.$user->id,
            'phone_number' => 'nullable|max:25',
            'role' => 'sometimes',
        ]);

        $data = $request->except('_token', '_method');

        $results = $this->userService->updateUser($data, $user);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        if(user()->id == $user->id)
        {
            return redirect()->back()->with('message', "Profile Successfully updated.");
        }

        request()->session()->flash('message', $results->message);
        return redirect()->route('tasks.users.index');
    }

    public function changeStatus(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        $result = $this->userService->changeStatus($user->status?0:1,$user);

        return $this->responseJson($result);
    }

    public function resetPassword(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        $result = $this->userService->resetPassword($user);

        return $this->responseJson($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = $this->userService->findUserById($id);

        $result = $this->userService->deleteUser($user);

        return $this->responseJson($result);
    }
}
