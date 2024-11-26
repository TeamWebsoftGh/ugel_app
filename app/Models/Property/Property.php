<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_code',
        'property_name',
        'image',
        'icon',
        'number_of_units',
        'status',
        'is_active',
        'description',
        'property_type_id'
        
    ];

    public function getUpdatedAtAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function property_type()
    {
        return $this->belongsTo(PropertyType::class)->withDefault(['name' => 'N/A']);
    }

    
}
