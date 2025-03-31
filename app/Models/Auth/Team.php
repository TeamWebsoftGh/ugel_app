<?php

namespace App\Models\Auth;


use App\Abstracts\Model;

class Team extends Model
{
    //
    protected $fillable = ['name', 'user_type', 'description', 'company_id', 'created_by'];
}
