<?php

namespace App\Models\Property;


use App\Abstracts\Model;
use App\Models\Client\Client;

class Review extends Model
{
    //
    protected $fillable = [
        'client_id',
        'property_id',
        'rating',
        'comment',
        'company_id',
        'created_by',
        'is_active'
    ];


    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault();
    }
}
