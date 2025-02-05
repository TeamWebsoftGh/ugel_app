<?php
namespace App\Traits;

use App\Models\Settings\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

trait SmsTrait
{
    /**
     * Sends SMS notification.
     *
     * @param $phone_number
     * @param $message
     * @return void
     * @throws GuzzleException
     */
    public function sendSms($phone_number, $message)
    {
        $sms_service = settings("sms_service", 'yoovi');

        if(!is_array($phone_number)){
            $phone_number = array($phone_number);
        }

        $data['phone_number'] = $phone_number;
        $data['message'] = $message;

        if ($sms_service == 'yoovi') {
            return $this->sendSmsViaYoovi($data);
        }

        if ($sms_service == 'npontu') {
            return $this->sendSmsViaNpontu($data);
        }

        $request_data = [
            settings('sms_send_to_param_name') => $data['phone_number'],
            settings('sms_msg_param_name') => $data['message'],
        ];

        if (!empty($sms_settings['sms_param_1'])) {
            $request_data[$sms_settings['sms_param_1']] = $sms_settings['sms_param_val_1'];
        }
        if (!empty($sms_settings['sms_param_2'])) {
            $request_data[$sms_settings['sms_param_2']] = $sms_settings['sms_param_val_2'];
        }
        if (!empty($sms_settings['sms_param_3'])) {
            $request_data[$sms_settings['sms_param_3']] = $sms_settings['sms_param_val_3'];
        }
        if (!empty($sms_settings['sms_param_4'])) {
            $request_data[$sms_settings['sms_param_4']] = $sms_settings['sms_param_val_4'];
        }
        if (!empty($sms_settings['sms_param_5'])) {
            $request_data[$sms_settings['sms_param_5']] = $sms_settings['sms_param_val_5'];
        }
        if (!empty($sms_settings['sms_param_6'])) {
            $request_data[$sms_settings['sms_param_6']] = $sms_settings['sms_param_val_6'];
        }
        if (!empty($sms_settings['sms_param_7'])) {
            $request_data[$sms_settings['sms_param_7']] = $sms_settings['sms_param_val_7'];
        }
        if (!empty($sms_settings['sms_param_8'])) {
            $request_data[$sms_settings['sms_param_8']] = $sms_settings['sms_param_val_8'];
        }
        if (!empty($sms_settings['sms_param_9'])) {
            $request_data[$sms_settings['sms_param_9']] = $sms_settings['sms_param_val_9'];
        }
        if (!empty($sms_settings['sms_param_10'])) {
            $request_data[$sms_settings['sms_param_10']] = $sms_settings['sms_param_val_10'];
        }

        $client = new Client();

        $headers = [];
        if (!empty($sms_settings['sms_header_1'])) {
            $headers[$sms_settings['sms_header_1']] = $sms_settings['sms_header_val_1'];
        }
        if (!empty($sms_settings['sms_header_2'])) {
            $headers[$sms_settings['sms_header_2']] = $sms_settings['sms_header_val_2'];
        }
        if (!empty($sms_settings['sms_header_3'])) {
            $headers[$sms_settings['sms_header_3']] = $sms_settings['sms_header_val_3'];
        }

        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }

        if (empty($sms_settings['sms_url'])) {
            return false;
        }

        if ($sms_settings['sms_request_method'] == 'get') {
            $response = $client->get($sms_settings['url'] . '?' . http_build_query($request_data), $options);
        } else {
            $options['form_params'] = $request_data;

            $response = $client->post($sms_settings['url'], $options);
        }

        return $response;
    }

    public function sendVoice($phone_number, $file)
    {
        $voice_id = settings('yoovi_voice_id','0540000000');
        $recipients = is_array($phone_number) ? $phone_number : array($phone_number);

        // Prepare multipart data
        $multipart = [
//            [
//                'name'     => 'recipients',
//                'contents' => json_encode($recipients),
//            ],
//            [
//                'name'     => 'voice_id',
//                'contents' => $voice_id,
//            ],
            [
                'name'     => 'retry',
                'contents' => 'true',
            ],
            [
                'name'     => 'callback_url',
                'contents' => route("callback.yoovi"),
            ],
            [
                'name'     => 'voice_file',
                'contents' => fopen(public_path($file), 'r'),
                'filename' => basename($file),
            ],
        ];

        // Add each recipient as a separate field
        foreach ($recipients as $recipient) {
            $multipart[] = [
                'name'     => 'recipients[]',
                'contents' => $recipient,
            ];
        }


        try {

            $full_url = settings('yoovi_sms_url').'/api/v2/sms/voice/send';
            $client = new  Client([
                'base_uri' => $full_url,
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            Log::channel('yoovi')->info("Request sent to $full_url", ['method' => "POST", 'options' => $multipart]);

            $response = $client->post('', [
                'headers' => [
                    'api-key'       => settings('yoovi_sms_api_key'),
                  //  'Accept'        => 'application/json',
                ],
                'multipart' => $multipart,
            ]);
            Log::channel('yoovi')->info("Response received from $full_url", ['response' => $response->getBody()->getContents()]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed'];
            }

            return $body;
        }catch (ConnectException | \Exception | RequestException $e) {
            log_error(format_exception($e), null, 'send-bulk-voice-failed');
            return (object)['code' => 'failed'];
        }
    }

    public function sendWhatAppMessages($phone_number, $text, $type = null, $file_type = null, $media_id = null)
    {
        $base_url = settings('whatsapp_base_url','https://graph.facebook.com');
        $phone_number_id = settings('whatsapp_phone_number_id');
        $access_token = settings('whatsapp_access_token');
        $version = settings('whatsapp_version','v21.0');

        if($type == "template")
        {
            if($file_type)
            {
                $payload = [
                    "messaging_product" => "whatsapp",
                    "to" => format_ghana_phone_number($phone_number),
                    "type" => "template",
                    "template" => [
                        "name" => $text,
                        "language" => [
                            "code" => "en"
                        ],
                        "components" => [
                            [
                                "type" => "header",
                                "parameters" => [
                                    [
                                        "type" => "$file_type",
                                        "$file_type" => [
                                            "id" => $media_id
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }else{
                $payload = [
                    "messaging_product" => "whatsapp",
                    "to" => format_ghana_phone_number($phone_number),
                    "type" => "template",
                    "template" =>  [
                        "name" => $text,
                        "language" => [
                            "code" => "en",
                        ]
                    ]
                ];
            }

        }else{
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => format_ghana_phone_number($phone_number),
                "type" => "text",
                "text" =>  [
                    "preview_url" => true,
                    "body" => $text
                ]
            ];
        }

        try {
            $full_url = $base_url.'/'.$version.'/'.$phone_number_id.'/messages';

            $client = new  Client([
                'base_uri' => $full_url,
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            Log::channel('meta')->info("Request sent to $full_url", ['method' => "POST", 'options' => $payload]);

            $response = $client->post('', [
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
            ]);

            Log::channel('meta')->info("Response received from $full_url", ['response' => $response->getBody()->getContents()]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed'];
            }

            return $body;
        }catch (ConnectException | \Exception | RequestException $e) {
            log_error(format_exception($e), null, 'send-whatsapp-message-failed');
            return (object)['status' => 'failed', 'message' => $e->getMessage()];
        }
    }

    public function sendWhatAppMedia($phone_number, $media_type, $media_id)
    {
        $base_url = settings('whatsapp_base_url','https://graph.facebook.com');
        $phone_number_id = settings('whatsapp_phone_number_id');
        $access_token = settings('whatsapp_access_token');
        $version = settings('whatsapp_version','v21.0');

        if($media_type == "video")
        {
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => format_ghana_phone_number($phone_number),
                "type" => $media_type,
                "$media_type" =>  [
                    "id" => $media_id,
                ]
            ];
        }else if($media_type =="document")
        {
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => format_ghana_phone_number($phone_number),
                "type" => "document",
                "document" =>  [
                    "id" => $media_id,
                ]
            ];
        }else{
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => format_ghana_phone_number($phone_number),
                "type" => $media_type,
                "$media_type" =>  [
                    "id" => $media_id,
                ]
            ];
        }

        try {
            $full_url = $base_url.'/'.$version.'/'.$phone_number_id.'/messages';

            $client = new  Client([
                'base_uri' => $full_url,
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            Log::channel('meta')->info("Request sent to $full_url", ['method' => "POST", 'options' => $payload]);

            $response = $client->post('', [
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
            ]);

            Log::channel('meta')->info("Response received from $full_url", ['response' => $response->getBody()->getContents()]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed'];
            }

            return $body;
        }catch (ConnectException | \Exception | RequestException $e) {
            log_error(format_exception($e), null, 'send-whatsapp-message-failed');
            return (object)['status' => 'failed', 'message' => $e->getMessage()];
        }
    }

    public function uploadWhatAppMedia($file)
    {
        $base_url = settings('whatsapp_bae_url','https://graph.facebook.com');
        $phone_number_id = settings('whatsapp_phone_number_id');
        $access_token = settings('whatsapp_access_token');
        $version = settings('whatsapp_version','v21.0');

        try {
            $full_url = $base_url.'/'.$version.'/'.$phone_number_id.'/media';

            $client = new  Client([
                'base_uri' => $full_url
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            // Prepare multipart data
            $multipart = [
                [
                    'name' => 'messaging_product',
                    'contents' => 'whatsapp'
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen(public_path($file), 'r'),
                    'filename' => basename($file),
                ],
            ];

            Log::channel('meta')->info("Request sent to $full_url", ['method' => "POST", 'options' => $multipart]);

            $response = $client->post('', [
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
//                    'Accept'        => 'application/json',
                ],
                'multipart' => $multipart,
            ]);
            Log::channel('meta')->info("Response received from $full_url", ['response' => $response->getBody()->getContents()]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed', 'id' => null];
            }

            return $body;
        }catch (ConnectException | \Exception | RequestException $e) {
            log_error(format_exception($e), null, 'send-whatsapp-media-failed');
            return (object)['status' => 'failed', 'id' => null];
        }
    }

    private function sendSmsViaYoovi($data)
    {
        // Prepare the payload
        $payload = [
            'sender'     => settings('yoovi_sms_send_id'),
            'message'    => $data['message'],
            'recipients' => $data['phone_number'],
            'callback_url' => route("callback.yoovi"),
        ];

        if (empty(settings('yoovi_sms_url')) || empty(settings('yoovi_sms_api_key')) || empty(settings('yoovi_sms_send_id'))) {
            return (object)['status' => 'failed'];
        }

        try {
            $full_url = settings('yoovi_sms_url').'/api/v2/sms/send';
            $client = new  Client([
                'base_uri' => $full_url,
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            Log::channel('yoovi')->info("Request sent to $full_url", ['method' => "POST", 'options' => $payload]);


            $response = $client->post('', [
                'headers' => [
                    'api-key'       => settings('yoovi_sms_api_key'),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);
            Log::channel('yoovi')->info("Response received from $full_url", ['response' => $response->getBody()->getContents()]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed'];
            }

            return $body;
        } catch (ConnectException | \Exception | RequestException $e) {
            log_error(format_exception($e), null, 'send-sms-failed');
            return (object)['status' => 'failed'];
        }
    }

    public function checkYooviBalance()
    {
        if (empty(settings('yoovi_sms_url')) || empty(settings('yoovi_sms_api_key')))
        {
            return (object)['status' => 'failed'];
        }

        try {
            $client = new  Client([
                'base_uri' => settings('yoovi_sms_url'),
                //'timeout'  => 30.0, // Adjust the timeout as needed
            ]);

            $response = $client->get('/api/v2/clients/balance-details', [
                'headers' => [
                    'api-key'       => settings('yoovi_sms_api_key'),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
            ]);

            $body = json_decode($response->getBody());

            if (!is_object($body)) {
                return (object)['status' => 'failed'];
            }

            if($body->status == 'success')
            {
                Configuration::updateOrCreate([
                    'option_key' => 'yoovi_sms_balance'
                ], ['option_value' => $body->data->sms_balance]);
                Configuration::updateOrCreate([
                    'option_key' => 'yoovi_main_balance'
                ], ['option_value' =>  $body->data->main_balance]);
            }

            return $body;
        }catch (\Exception $e) {
            log_error(format_exception($e), null, 'send-balance-enquiry-failed');
            return (object)['status' => 'failed'];
        }

    }

    private function sendSmsViaNpontu($data)
    {
        $request_data = [
            'username' => settings('npontu_sms_username'),
            'password' => settings('npontu_sms_password'),
            'source' => settings('npontu_sms_source'),
            'destination' => $data['phone_number'],
            'message' =>  $data['message'],
        ];

        if (empty(settings('npontu_sms_url')) || empty(settings('npontu_sms_username')) || empty(settings('npontu_sms_password'))) {
            return false;
        }

        $client = new Client();
        //$numbers = explode(',', trim($data['mobile_number']));

        if(strtolower(settings('npontu_sms_request_method', 'GET')) == "post"){
            $options['form_params'] = $request_data;

            $response = $client->post(settings('npontu_sms_url'), $options);
        }

        $response = $client->get(settings('npontu_sms_url') . '?' . http_build_query($request_data), []);
    }

    private function sendSmsViaNexmo($data)
    {
        $sms_settings = $data['sms_settings'];

        if (empty($sms_settings['nexmo_key']) || empty($sms_settings['nexmo_secret'])) {
            return false;
        }

        Config::set('nexmo.api_key', $sms_settings['nexmo_key']);
        Config::set('nexmo.api_secret', $sms_settings['nexmo_secret']);

        $nexmo = app('Nexmo\Client');
        $numbers = explode(',', trim($data['mobile_number']));

        foreach ($numbers as $number) {
            $nexmo->message()->send([
                'to'   => $number,
                'from' => $sms_settings['nexmo_from'],
                'text' => $data['message']
            ]);
        }
    }

    /**
     * Generates Whatsapp notification link
     *
     * @param  array $data
     * @return string
     */
    public function getWhatsappNotificationLink($data)
    {
        //Supports only integers without leading zeros
        $whatsapp_number = abs((int) filter_var($data['mobile_number'], FILTER_SANITIZE_NUMBER_INT));
        $text = $data['whatsapp_text'];

        $base_url = settings('whatsapp_base_url') . '/' . $whatsapp_number;

        return $base_url . '?text=' . urlencode($text);
    }

}
