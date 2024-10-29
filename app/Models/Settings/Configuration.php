<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_key',
        'option_value'
    ];

    public $timestamps = false;

    static function get_setting($key)
    {
        $records = self::where('option_key', $key)->get();

        if ($records->count() > 0) {
            $string = $records->first()->option_value;

            if (is_string($string) && is_array(json_decode($string, true))) {
                return json_decode($string);
            } else {
                return $string;
            }
        }

        return null;
    }
}
