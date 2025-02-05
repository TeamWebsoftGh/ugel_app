<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\IClientService;
use App\Traits\JsonResponseTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;

class GeneralController extends MobileController
{
    use SmsTrait, JsonResponseTrait;

    private IClientService $clientService;

    /**
     * Create a new controller instance.
     *
     * @param IClientService $client
     */
    public function __construct(IClientService $client)
    {
        $this->clientService = $client;
    }

    public function sendOtp()
    {
        $otp = generate_otp();

        $this->sendSms();
        return $this->sendResponse(ResponseType::SUCCESS, "OTP sent");
    }

    public function validateOtp(string $country)
    {
        return $this->sendResponse(ResponseType::SUCCESS, "Record Found");
    }


}
