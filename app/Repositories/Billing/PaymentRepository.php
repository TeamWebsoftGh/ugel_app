<?php

namespace App\Repositories\Billing;

use App\Models\Billing\Payment;
use App\Models\Payment\PaymentGateway;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IPaymentRepository;


class PaymentRepository extends BaseRepository implements IPaymentRepository
{
    /**
     * @var mixed
     */

    /**
     * HubtelCheckoutRepository constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        parent::__construct($payment);
        $this->model = $payment;
    }

    public function listPayments(array $filter = [], string $orderBy= 'updated_at', string $sort = 'desc')
    {
        $query = $this->getFilteredList();
        return $query->orderBy($orderBy, $sort);
    }

    /**
     * @param array $data
     * @param Order $order
     */
    public function processPayment(array $data, Order $order){
        $service_url = 'https://orchard-api.anmgw.com/sendRequest';
        $service_id = "246";
        $client_key = "uzNMRVZAkefpSiB0yI5VRM5EJOx6aVp9Ft8b0rpyjeaMeARq//oB4HlHl2pDHbB7ZXwFx7TgX58XBi/36kCjKw==";
        $secret_key = "17ppnrnvIk5Y/BUZeONTqVKuI1KYQEOlQi+Vrv3LIaaxOrhYtdjK7Y4cwwJbBydwU+dZc2tpzZSfyDsLPNq9rQ==";

        $data = array(
            'service_id' => $service_id,
            'trans_type' => 'CTM',
            'customer_number' => '0242734804',
            'amount' => $order->total_amount,
            'exttrid' => rand(100000, 999999),
            'reference' => 'Application Fees',
            'nw' => $data['vendor'], //AIR/VOD
            'callback_url' => route('applicant.postgraduate.edit'),
            'ts' => date('Y-m-d H:i:s')
            //'voucher_code' => 123456
        );
        $data_string = json_encode($data);
        $signature =  hash_hmac ( 'sha256' , $data_string , $secret_key );
        $auth = $client_key.':'.$signature;

        $ch = curl_init($service_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: '.$auth,
                'Content-Type: application/json',
                'timeout: 180',
                'open_timeout: 180'
            )
        );

        $result = curl_exec($ch);
        echo $result;
    }

    public function checkPaymentStatus()
    {
        $applicant  = user('applicant');
        if (request()->has('ref')) {
            $response = $this->http->request('GET', 'bill/refstatus?ref='.request()->input('ref'));
            $result = $response->getBody()->getContents();
            $result = json_decode($result,TRUE);

            if ($response->getStatusCode() == 200 && $result['Status'] === 'SUCCESSFUL')
            {
                $this->createPayment($result, $applicant);
                return true;
            }

            $logType = GeneralSettings::getByName('admission-activity')->id;
            $msg = 'Payment Failed: Ref: '.request()->input('ref');
            log_activity($msg, $applicant, $logType);
        }
        return false;
    }
}
