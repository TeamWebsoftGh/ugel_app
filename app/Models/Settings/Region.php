<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable =['name', 'is_active', 'description', 'country_id'];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

}
