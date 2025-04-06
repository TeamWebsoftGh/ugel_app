<?php

namespace App\Models\CustomerService;


use App\Abstracts\Model;

class SupportTopic extends Model
{
    //
    protected $fillable = ['name', 'short_name', 'description', 'team_id'];
}
