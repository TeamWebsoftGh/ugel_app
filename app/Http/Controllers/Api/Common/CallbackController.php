<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Models\Communication\SmsAlert;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends MobileController
{
    use SmsTrait;

    public function handleYooviCallback(Request $request)
    {
        try {
            // Retrieve allowed IPs from the environment variable
            $allowedIps = explode(',', env('ALLOWED_IPS'));

            $sms = null;

            // Get the IP address of the incoming request
            $requestIp = $request->ip();

            // Check if the request IP is in the list of allowed IPs
            if (!in_array($requestIp, $allowedIps)) {
                Log::channel('yoovi')->warning('Unauthorized callback attempt from IP: ' . $requestIp);
                return response()->json(['code' => '01', 'msg' => 'Unauthorized Access'], 403);
            }

            // Retrieve callback data from the request
            $data = $request->all();

            // Log the received data
            Log::channel('yoovi')->info('Callback received', $data);


            if(isset($data['campaign_id']))
            {
                $sms = SmsAlert::firstWhere('campaign_id', $data['campaign_id']);
            }elseif (isset($data['sms_id'])){
                $sms = SmsAlert::firstWhere('campaign_id', $data['sms_id']);
            }

            if($sms != null)
            {
                $sms->status = $data['status'];
                $sms->save();
            }

            return response()->json(['code' => '00', 'msg' => 'Callback received successfully'], 200);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $trans??new SmsAlert(), 'receive-yoovi-callback-failed');
            return response()->json(['code' => '05', 'msg' => ResponseMessage::DEFAULT_ERROR], 200);
        }
    }

}
