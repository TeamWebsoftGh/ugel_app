<?php

namespace App\Abstracts\Http;

use App\Services\Helpers\Response;
use App\Traits\JsonResponseTrait;
use App\Traits\Permissions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

abstract class MobileController extends BaseController
{
    use AuthorizesRequests, Permissions, ValidatesRequests, JsonResponseTrait;

    protected Response $response;

    /**
     * ServiceBase constructor.
     */
    public function __construct()
    {
        $this->response = new Response();
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($code, $message, $data = null, LengthAwarePaginator $awarePaginator= null)
    {
        if($data == null)
        {
            $response = [
                'status_code' => $code,
                'message' => $message,
            ];
        }else{

            $response = [
                'status_code' => $code,
                'message' => $message,
                'data' => $data,
            ];

            // Add pagination metadata if data is an instance of LengthAwarePaginator
            if ($awarePaginator instanceof LengthAwarePaginator) {
                $response['meta'] = [
                    'total' => $awarePaginator->total(),
                    'count' => $awarePaginator->count(),
                    'per_page' => $awarePaginator->perPage(),
                    'current_page' => $awarePaginator->currentPage(),
                    'total_pages' => $awarePaginator->lastPage(),
                ];
            }
        }
        return response()->json($response);
    }

    protected function paginate($items, $perPage, $page, $options = [])
    {
        if($page < 1)
        {
            $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
            $items = $items instanceof Collection ? $items : Collection::make($items);
            $perPage = $items->count();
            return new LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $items->count(),
                $perPage,
                $page,
                $options
            );
        }
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
}
