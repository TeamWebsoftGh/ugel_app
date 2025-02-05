<?php

namespace App\Models\Settings;

use App\Models\Delegate\Constituency;
use App\Models\Election\ParliamentaryCandidate;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable =['name', 'is_active', 'description', 'country_id'];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function constituencies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Constituency::class);
    }

    public function candidates(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(ParliamentaryCandidate::class, Constituency::class);
    }
}
