<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Models\Billing\Booking;
use App\Models\Billing\Invoice;
use App\Models\Billing\Payment;
use App\Models\Client\Client;
use App\Models\CustomerService\MaintenanceRequest;
use App\Models\CustomerService\SupportTicket;
use App\Models\Payment\Wallet;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NumberGenerator extends Model
{
    use HasFactory;

    public $timestamps = false;

    static function gen($generatable_type)
    {
        $obj = self::where('generatable_type', $generatable_type)->get()->first();

        if ($obj) {
            $obj->last_generated_value ++;
            $generated_number = sprintf('%06d', $obj->last_generated_value);
        } else {
            $obj = new NumberGenerator();
            $obj->company_id = user()?->company_id??1;
            $obj->generatable_type = $generatable_type;
            $generated_number = "000001";
        }

        $obj->last_generated_value = $generated_number;
        $obj->save();

        return self::get_prefix($generatable_type).$generated_number;
    }

    private static function get_prefix($generatable_type)
    {
        $prefix_list = [
            Property::class => 'UGEL',
            MaintenanceRequest::class => 'MT',
            Booking::class => 'B',
            Invoice::class => 'INV',
            SupportTicket::class => 'ST',
            Client::class => 'UG',
            Payment::class => 'UGEL',
            Wallet::class => 'WAL',
        ];

        return (isset($prefix_list[$generatable_type])) ? $prefix_list[$generatable_type] : NULL;
    }
}
