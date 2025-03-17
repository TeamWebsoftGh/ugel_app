<?php

namespace App\Models\CustomerService;

use App\Models\Common\Comment;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    //
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
