<?php

namespace App\Http\Controllers\Client;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Client\Client;
use App\Services\Interfaces\IClientService;
use App\Services\Interfaces\IUserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private IUserService $userService;
    private IClientService $clientService;

    /**
     * Create a new controller instance.
     *
     * @param IUserService $user
     * @param IClientService $client
     */
    public function __construct(IUserService $user, IClientService $client)
    {
        parent::__construct();
        $this->middleware(['permission:update-customers'], ['only' => ['changeStatus', 'resetPassword']]);

        $this->userService = $user;
        $this->clientService = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $users = $this->clientService->listClients($data);
            return datatables()->of($users)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('name', function ($row)
                {
                    return $row->fullname;
                })
                ->addColumn('type', function ($row)
                {
                    return $row->clientType->name;
                })
                ->addColumn('phone_number', function ($row)
                {
                    return $row->phone_number??"N/A";
                })
                ->addColumn('category', function ($row)
                {
                    return ucwords($row->clientType->category);
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-customers'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-customers'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('client.clients.index');
    }

    public function organizations(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $data['filter_category'] = "business";
            $users = $this->clientService->listClients($data);
            return datatables()->of($users)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('name', function ($row)
                {
                    return $row->fullname;
                })
                ->addColumn('type', function ($row)
                {
                    return $row->clientType->name;
                })
                ->addColumn('phone_number', function ($row)
                {
                    return $row->phone_number??"N/A";
                })
                ->addColumn('category', function ($row)
                {
                    return ucwords($row->clientType->category);
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-customers'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-customers'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('client.clients.organization');
    }


    public function students(Request $request)
    {
        if (request()->ajax())
        {
            $data = $request->all();
            $data['filter_client_code'] = "student";
            $users = $this->clientService->listClients($data);
            return datatables()->of($users)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->addColumn('name', function ($row)
                {
                    return $row->fullname;
                })
                ->addColumn('type', function ($row)
                {
                    return $row->clientType->name;
                })
                ->addColumn('phone_number', function ($row)
                {
                    return $row->phone_number??"N/A";
                })
                ->addColumn('category', function ($row)
                {
                    return ucwords($row->clientType->category);
                })
                ->addColumn('action', function ($data)
                {
                    $button = '<button type="button" name="show" data-id="' . $data->id . '" class="dt-show btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="show"><i class="las la-eye"></i></button>';
                    $button .= '&nbsp;';
                    if (user()->can('update-customers'))
                    {
                        $button .= '<button type="button" name="edit" data-id="' . $data->id . '" class="dt-edit btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="las la-edit"></i></button>';
                        $button .= '&nbsp;';
                    }
                    if (user()->can('delete-customers'))
                    {
                        $button .= '<button type="button" name="delete" data-id="' . $data->id . '" class="dt-delete btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash"></i></button>';
                    }

                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('client.clients.student');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function create(Request $request)
    {
        $client = new Client();
        $client->is_active = 1;
        return view('client.clients.create', compact("client"));
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
            'client_type_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,'.$request->input("id"),
            'email' => 'required|unique:users,email,'.$request->input("id"),
            'phone_number' => 'nullable|phone',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $user = $this->clientService->findClientById($request->input("id"));
            $results = $this->clientService->updateClient($data, $user);
        }else{
            $results = $this->clientService->createClient($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('admin.customers.index');
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
        $client = $this->clientService->findClientById($id);
        $result = $this->clientService->deleteClient($client);

        return $this->responseJson($result);
    }
}
