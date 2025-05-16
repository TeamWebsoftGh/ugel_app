<?php

namespace App\Models\Auth;


use App\Abstracts\Model;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device',
        'ip_address',
        'user_agent',
        'location',
        'is_verified',
    ];
}
