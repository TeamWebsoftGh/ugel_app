<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class)->withDefault();
    }
}
