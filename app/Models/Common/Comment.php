<?php

namespace App\Models\Common;

use App\Models\Auth\User;
use App\Models\Loan\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'subject',
        'commentable_id',
        'commentable_type',
        'created_by',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
