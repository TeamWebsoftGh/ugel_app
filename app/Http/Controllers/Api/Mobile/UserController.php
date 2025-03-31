<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\Interfaces\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends MobileController
{
    /**
     * @var IUserService
     */
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
     */
    public function index(Request $request)
    {
        // Get all request data
        $data = $request->all();

        // Get the collection of polling stations
        $items = $this->userService->listUsers($data);

        // Convert to a collection if it's not already one
        if (!$items instanceof Collection) {
            $items = collect($items);
        }

        //Manually paginate the collection
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 25);;
        $paginatedItems = $this->paginate($items, $perPage, $page);

        // Transform the items using a resource collection
        $items = UserResource::collection($paginatedItems);

        // Return the paginated response
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $items, $paginatedItems);
    }

    public function create(Request $request)
    {
        $data = $this->userService->getCreateUser($request->all());
        $data['roles'] = RoleResource::collection($data['roles']);
        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $data);
    }
    /**
     * Store a newly created resource in storage.
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

        $data = $request->all();
        $data['is_active']= 1;

        $results = $this->userService->createUser($data);
        $results->data = new UserDetailResource($results->data);
        return $this->apiResponseJson($results);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->findUserById($id);
        $item = new UserDetailResource($user);

        return $this->sendResponse("000", ResponseMessage::DEFAULT_SUCCESS, $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|phone|unique:users,phone_number,'.$id,
            'email' => 'nullable|email|unique:users,email,'.$id,
            'role' => 'required',
        ]);

        $data = $request->all();

        $user = $this->userService->findUserById($id);
        $results = $this->userService->updateUser($data, $user);

        $results->data = new UserDetailResource($results->data);
        return $this->apiResponseJson($results);
    }

    public function changeStatus(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        $results = $this->userService->changeStatus($user->is_active?0:1,$user);

        return $this->apiResponseJson($results);
    }

    public function resetPassword(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);
        $results = $this->userService->resetPassword($user);
        return $this->apiResponseJson($results);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userService->findUserById($id);
        $results = $this->userService->deleteUser($user);
        return $this->apiResponseJson($results);
    }
}
