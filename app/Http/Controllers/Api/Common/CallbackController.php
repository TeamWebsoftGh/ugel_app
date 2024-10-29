<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\MobileController;
use App\Models\Payment\Transaction;
use App\Models\Payment\CallbackRequest;
use App\Services\Helpers\FundTransferHelper;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends MobileController
{
    use SmsTrait;

    public function handleNsanoCallback(Request $request)
    {
        try {
            // Retrieve allowed IPs from the environment variable
            $allowedIps = explode(',', env('ALLOWED_IPS'));

            // Get the IP address of the incoming request
            $requestIp = $request->ip();

            // Check if the request IP is in the list of allowed IPs
            if (!in_array($requestIp, $allowedIps)) {
                Log::channel('nsano')->warning('Unauthorized callback attempt from IP: ' . $requestIp);

                return response()->json(['code' => '01', 'msg' => 'Unauthorized IP'], 403);
            }

            // Retrieve callback data from the request
            $data = $request->all();

            // Log the received data
            Log::channel('nsano')->info('Callback received', $data);

            $trans = Transaction::firstWhere('reference', $data['reference']);

            if($trans != null)
            {
                $trans->reference = $data['reference'];
                $trans->message = $data['msg'];
                $trans->transaction_id = $data['transactionID'];
                if($data['code'] == "00")
                {
                    $trans->status = "successful";
                }else{
                    $trans->status = "failed";
                }

                $trans->save();

                (new FundTransferHelper)->updateTransaction($trans);
            }

            // Save or update the callback request in the database
            CallbackRequest::UpdateRequest($data['reference'], json_encode($data));

            return response()->json(['code' => '00', 'msg' => 'Callback received successfully'], 200);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $trans??new Transaction(), 'receive-nsano-callback-failed');
            return response()->json(['code' => '05', 'msg' => 'An error occurred.'], 200);
        }
    }

}
