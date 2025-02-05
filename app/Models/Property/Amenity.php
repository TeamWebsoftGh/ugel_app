<?php

namespace App\Models\Property;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Amenity extends Model
{
    protected $fillable  = [
        'name',
        'is_active',
        'created_by',
        'created_from',
        'short_name',
        'company_id'
    ];

    public function amenentable()
    {
        return $this->morphTo();
    }

}
