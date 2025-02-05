<?php

namespace App\Models\Auth;


use App\Abstracts\Model;

class UserOtp extends Model
{
    protected $fillable = ['user_id', 'otp', 'expire_at', 'medium'];

}
