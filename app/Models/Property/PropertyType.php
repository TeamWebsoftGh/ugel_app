<?php

namespace App\Models\Property;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'property_category_id',
        'short_name'
    ];

    public function getUpdatedAtAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function property_category()
    {
        return $this->belongsTo(PropertyCategory::class)->withDefault(['name' => 'N/A']);
    }

}
