<?php

namespace App\Models\Auth;


use App\Abstracts\Model;

class Team extends Model
{
    //
    protected $fillable = ['name', 'user_type','code', 'description', 'company_id', 'created_by', 'team_lead_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, TeamUser::class, 'team_id', 'user_id');
    }
}
