<?php

namespace App\Traits;

use App\Constants\ResponseType;
use App\Services\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait JsonResponseTrait
{

    protected function responseJson(Response $result)
    {
        $response = $this->getResponse($result);

        return response()->json([
            'status' => $response->status,
            'message' => $response->message,
            'data' => $response->data
        ]);
    }

    protected function apiResponseJson(Response $result)
    {
        $response = $this->getResponse($result);

        return response()->json([
            'status_code' => $response->code,
            'message' => $response->message,
            'data' => $result->data
        ]);
    }

     /**
     * @param Request $request
     * @param string $type
     * @param array $data['message','data']
     * @param string $route
     * @return Response
     */
    protected function GeneralResponse(Request $request, $type, $data=[], $route=null)
    {
        $message = $type=="error"?error_message($data['message']):success_message($data['message']);
        $response = [
            'message' => $message,
            'data' => $data['data'],
        ];
        if ($request->ajax()) {
            return response()->json($response);
        }else{
            if ($route) {
               return redirect($route)->with($response);
            }
            return redirect()->back()->with($response);
        }
    }

    /**
     * @param Response $result
     * @return Response
     */
    protected function getResponse(Response $result): Response
    {
        $response = new Response();
        $code = "005";
        if ($result->status == ResponseType::SUCCESS) {
            $response->message = is_null($result->message) ? "Action was successful" : $result->message;
            $response->status = "success";
            $code = "000";
        } else if ($result->status == ResponseType::UNAUTHORIZED) {
            $response->message = is_null($result->message) ? "You are not authorized to perform this action" : $result->message;
            $response->status = "UNAUTHORIZED";
            $code = "403";
        } else {
            $response->message = is_null($result->message) ? "Could not perform this action" : $result->message;
            $response->status = "ERROR";
        }

        if(is_null($response->code) || $response->code == ""){
            $response->code = $code;
        }

        return $response;
    }
}
