<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentGatewaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inst = 'Swift Code:  GHB009' . PHP_EOL .
            'Bank Name:  ABSA BANK' . PHP_EOL .
            'Bank Address:  4320 Banana Road, Accra, GE 17112' . PHP_EOL .
            'Bank Account #:  220148' . PHP_EOL .
            'Beneficiary Name: UGEL' . PHP_EOL .
            'Beneficiary Address: Accra, Ghana' . PHP_EOL .
            'Beneficiary Account #: 220148';

        $momo = 'Account Name:  UGEL' . PHP_EOL .
            'MTN Account #:  0242734804' . PHP_EOL .
            'AT Account #:  0271734804' . PHP_EOL .
            'TELECEL Account #:  0242734804' . PHP_EOL .
            'Beneficiary Account #: 220148';

        $method = new PaymentGateway();
        $method->name = "Wire Transfer";
        $method->description = "Pay via Wire Transfer";
        $method->mode = "offline";
        $method->instruction = $inst;
        $method->slug = Str::slug('Wire Transfer');
        $method->settings = [
            'requires_transaction_number' => true,
            'requires_uploading_attachment' => false,
            'reference_field_label' => 'Transaction Number',
            'attachment_field_label' => null,
        ];
        $method->save();


        $method = new PaymentGateway();
        $method->name = "Bank Deposit";
        $method->description = "Pay via Bank Deposit";
        $method->mode = "offline";
        $method->instruction = $inst;
        $method->slug = Str::slug('Bank Deposit');
        $method->settings = [
            'requires_transaction_number' => true,
            'requires_uploading_attachment' => true,
            'reference_field_label' => 'Transaction Number',
            'attachment_field_label' => 'Attach a scan copy of the deposit slip',
        ];
        $method->save();

        $method = new PaymentGateway();
        $method->name = "Mobile Money(Offline)";
        $method->description = "Offline Mobile Money Transfer";
        $method->mode = "offline";
        $method->instruction = $momo;
        $method->slug = Str::slug('Mobile Money(Offline)');
        $method->settings = [
            'requires_transaction_number' => true,
            'requires_uploading_attachment' => false,
            'reference_field_label' => 'Transaction Number',
            'attachment_field_label' => null,
        ];
        $method->save();

        $method = new PaymentGateway();
        $method->name = "Paystack";
        $method->description = "Paystack";
        $method->mode = "online";
        $method->is_active = 1;
        $method->slug = Str::slug('Paystack');
        $method->settings = [
            'public_key' => 'pk_test_ff522f7aeb4d235af801e6ee3bd06874e4e77e2a',
            'secret_key' => 'sk_test_39e89b09ccbee9444f8e46a9476513170c2c8c4c',
            'base_url' => 'https://api.paystack.co',
            'merchant_email' => 'unicodeveloper@gmail.com',
        ];

        $method->save();
    }
}
