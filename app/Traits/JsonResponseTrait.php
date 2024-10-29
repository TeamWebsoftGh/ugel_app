<?php

namespace App\Traits;

use App\Constants\ResponseType;
use App\Services\Helpers\Response;
use Illuminate\Support\Facades\Storage;

trait JsonResponseTrait
{

    protected function responseJson(Response $result)
    {
        $response = new Response();
        if ($result->status == ResponseType::SUCCESS)
        {
            $response->message = is_null($result->message) ? "Action was successful" : $result->message;
            $response->status = "success";
        }
        else if ($result->status == ResponseType::UNAUTHORIZED)
        {
            $response->message = is_null($result->message) ? "You are not authorized to perform this action" : $result->message;
            $response->status = "unauthorize";
        }
        else
        {
            $response->message = is_null($result->message) ? "Could not perform this action" : $result->message;
            $response->status = "error";
        }

        return response()->json([
            'Result' => $response->status,
            'Message' => $response->message
        ]);
    }
}
