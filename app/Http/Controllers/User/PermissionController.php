<?php

namespace App\Http\Controllers\User;

use App\Abstracts\Http\Controller;
use App\Constants\ResponseType;
use App\Models\Auth\Permission;
use App\Services\Interfaces\IPermissionService;
use App\Traits\JsonResponseTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use JsonResponseTrait;
    /**
     * @var IPermissionService
     */
    private IPermissionService $permissionService;

    /**
     * PermissionController constructor.
     *
     * @param IPermissionService $permission
     */
    public function __construct(IPermissionService $permission)
    {
        parent::__construct();
        $this->permissionService = $permission;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $permission =  new Permission();
        $permissions = $this->permissionService->listPermissions('updated_at');
        if (request()->ajax())
        {
            return datatables()->of($permissions)
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
        return view('user-access.permissions.create', compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:permissions,id',
            'display_name' => 'required',
        ]);

        $data = $request->except('_token', '_method', 'id');

        if ($request->has("id") && $request->input("id") != null)
        {
            $role = $this->permissionService->findPermissionById($request->input("id"));
            $results = $this->permissionService->updatePermission($data, $role);
        }else{
            $results = $this->permissionService->createPermission($data);
        }

        if ($request->ajax()){
            return $this->responseJson($results);
        }

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }

        request()->session()->flash('message', $results->message);

        return redirect()->route('permissions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $permission = $this->permissionService->findPermissionById($id);

        if ($request->ajax()){
            return view('user-access.permissions.edit', compact('permission'));
        }

        return redirect()->route("permissions.index");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role = $this->permissionService->findPermissionById($id);
        $result = $this->permissionService->deletePermission($role);

        return $this->responseJson($result);
    }
}
