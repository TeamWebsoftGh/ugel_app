<?php

namespace App\Models\Memo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'sender_id',
        'message',
        'is_sent',
        'eloquentable_id',
        'eloquentable_type',
        'created_from',
        'created_by',
        'batch_no',
        'provider',
        'status',
        'schedule_time',
        'import_id'
    ];
}
