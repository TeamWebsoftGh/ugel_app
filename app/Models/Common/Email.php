<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'subject',
        'message',
        'line_1',
        'line_2',
        'line_3',
        'line_4',
        'line_5',
        'is_sent',
        'request_date',
        'cc',
        'bcc',
        'attachment',
        'eloquentable_id',
        'eloquentable_type',
        'button_url',
        'button_name',
        'message_type',
        'remarks',
        'emailable_type',
        'emailable_id',
        'company_id'
    ];

    public function emailable()
    {
        return $this->morphTo();
    }

    public function eloquentable()
    {
        return $this->morphTo();
    }
}
