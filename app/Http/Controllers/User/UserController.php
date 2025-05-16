<?php

namespace App\Http\Controllers\User;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Auth\User;
use App\Services\Auth\Interfaces\IUserService;
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
        $this->middleware(['permission:read-users'], ['only' => ['login', 'resetPassword']]);

        $this->userService = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = $this->userService->listUsers();
            return datatables()->of($data)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('username', function ($row)
                {
                    $url = asset($row->user_image);
                    $profile_photo = '<img src="'. $url .'" class="avatar-sm rounded-circle me-2"/>';

                    $name  = "<span class='text-body fw-medium'>".$row->username. "</span>";

                    return "<div class='d-flex'>
									<div class='mr-2'>".$profile_photo."</div>
									<div>"
                        .$name;
                    "</div>
								</div>";

                })
                ->addColumn('name', function ($row)
                {
                    return $row->fullname;
                })
                ->addColumn('status', function ($row)
                {
                    return $row->is_active?"Active":"Inactive";
                })
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-users'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-users'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'username'])
                ->make(true);
        }

        return view('user-access.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function create(Request $request)
    {
        $user = new User();
        $roles = $this->userService->listRoles();

        return view('user-access.users.edit', compact('user', 'roles'));
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
            'phone_number' => 'required|phone|unique:users,phone_number,'.$request->input("id"),
            'email' => 'nullable|email|unique:users,email,'.$request->input("id"),
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
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request, $id)
    {
        $data['roles'] = $this->userService->listRoles();
        $data['user'] = $this->userService->findUserById($id);
        $data['roleId'] = optional($data['user']->roles()->first())->id;

        if ($request->ajax()){
            return view('user-access.users.edit', $data);
        }

        return redirect()->route("users.create", ["userId" => $id]);
    }


    public function changeStatus(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        $result = $this->userService->changeStatus($user->is_active?0:1,$user);

        return $this->responseJson($result);
    }

    public function resetPassword(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        $result = $this->userService->resetPassword($user);

        return $this->responseJson($result);
    }

    public function bulkPasswordReset(Request $request)
    {
        $results = $this->userService->bulkPasswordReset();

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->back();
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
