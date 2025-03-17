<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = [
        'message',
        'subject',
        'commentable_id',
        'commentable_type',
        'created_by',
    ];
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
