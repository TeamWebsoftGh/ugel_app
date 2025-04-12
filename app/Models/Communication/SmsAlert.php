<?php

namespace App\Models\Communication;

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
        'type',
        'media_id',
        'media_url',
        'tem_type',
        'company_id',
        'campaign_id',
        'file',
        'file_type',
        'import_id'
    ];
}
