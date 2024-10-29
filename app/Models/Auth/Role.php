<?php

namespace App\Models\Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }
}
