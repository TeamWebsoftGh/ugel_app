<?php

namespace App\Models\CustomerService;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketComment extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'user_id', 'support_ticket_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format')." - h:i a");
    }
}
