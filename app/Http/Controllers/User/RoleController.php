<?php

namespace App\Http\Controllers\User;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Services\Interfaces\IRoleService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoleController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IRoleService
     */
    private IRoleService $roleService;

    /**
     * RoleController constructor.
     *
     * @param IRoleService $role
     */
    public function __construct(IRoleService $role)
    {
        parent::__construct();
        $this->roleService = $role;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $permissions =  Permission::all()->except([1,2,3,4,5]);
        $roles = $this->roleService->listRoles('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($roles)
                ->setRowId(function ($row)
                {
                    return $row->id;
                })
                ->addIndexColumn()
                ->setRowAttr([
                    'data-target' => function($row) {
                        return '#list-content';
                    },
                ])
                ->make(true);
        }
        $role = new Role();
        $attachedPermissionsArrayIds = [];
        return view('user-access.roles.create', compact('permissions','role', 'attachedPermissionsArrayIds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $permissions =  Permission::all()->except([1,2,3,4,5]);
        $roles = $this->roleService->listRoles('updated_at');
        $role = new Role();
        $attachedPermissionsArrayIds = [];
        return view('user-access.roles.create', compact('permissions', 'roles', 'role', 'attachedPermissionsArrayIds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,id',
            'display_name' => 'required',
            'status' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $role = $this->roleService->findRoleById($request->input("id"));
            $results = $this->roleService->updateRole($data, $role);
        }else{
            $results = $this->roleService->createRole($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('roles.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $role = $this->roleService->findRoleById($id);

        $attachedPermissionsArrayIds = $role->permissions()->pluck('id')->all();
        $permissions =  Permission::all()->except([1,2,3,4,5]);

        if ($request->ajax()){
            return view('user-access.roles.edit', compact('permissions', 'role', 'attachedPermissionsArrayIds'));
        }

        return redirect()->route('roles.create');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->roleService->findRoleById($id);
        $result = $this->roleService->deleteRole($role);

        return $this->responseJson($result);
    }
}
