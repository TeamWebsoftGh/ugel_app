<?php
namespace App\Traits;

use GuzzleHttp\Client;

trait SmsTrait
{
    /**
     * Sends SMS notification.
     *
     * @param  array $data
     * @return void
     */
    public function sendSms($phone_number, $message)
    {
        $sms_service = settings("sms_service", 'yoovi');

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

    private function sendSmsViaYoovi($data)
    {
        $request_data = [
            'api_key' => settings('yoovi_sms_api_key'),
            'action' => 'send-sms',
            'from' => settings('yoovi_sms_send_id'),
            'to' => $data['phone_number'],
            'sms' =>  $data['message'],
        ];

        if (empty(settings('yoovi_sms_url')) || empty(settings('yoovi_sms_api_key')) || empty(settings('yoovi_sms_send_id'))) {
            return false;
        }

        $client = new Client();
        //$numbers = explode(',', trim($data['mobile_number']));

        $client->get(settings('yoovi_sms_url') . '?' . http_build_query($request_data), []);
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

//        dd($response->getBody()->getContents());
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
