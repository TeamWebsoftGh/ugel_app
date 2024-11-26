<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'lease_amount',
        'lease_start_date',
        'lease_end_date',
        'country_id',
        'start_id',
        'city_id',
        'zip_code',
        'address',
        'map_link',
        'latitude',
        'longitude',
        'agent_commission_value',
        'agent_commission_type',
        'property_id'
    ];

    public function getUpdatedAtAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault(['name' => 'N/A']);
    }
}
