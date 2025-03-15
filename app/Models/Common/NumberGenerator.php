<?php

namespace App\Models\Common;

use App\Abstracts\Model;
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
            $obj->company_id = user()->company_id;
            $obj->generatable_type = $generatable_type;
            $generated_number = "000001";
        }

        $obj->last_generated_value = $generated_number;
        $obj->save();

        return self::get_prefix($generatable_type) . "-" . $generated_number;
    }

    private static function get_prefix($generatable_type)
    {
        $prefix_list = [
            Property::class => 'UGEL',
            'App\Models\Payment' => 'PMT',
            'App\Models\Wallet' => 'WAL',
            'App\Models\SupportTicket' => 'ST',
        ];

        return (isset($prefix_list[$generatable_type])) ? $prefix_list[$generatable_type] : NULL;
    }
}
