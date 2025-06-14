<?php

namespace App\Models\CustomerService;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Common\Comment;
use App\Models\Common\Priority;
use Carbon\Carbon;

class SupportTicket extends Model
{
    protected $appends = ["assignee_names"];

    protected $fillable = ['ticket_note', 'status', 'ticket_code', 'priority_id','phone_number', 'email',
        'client_id', 'description', 'remarks', 'subject','support_topic_id', 'company_id', 'created_by'];

    public function priority()
    {
        return $this->belongsTo(Priority::class)->withDefault();
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function supportTopic()
    {
        return $this->belongsTo(SupportTopic::class)->withDefault(['name' => "N/A"]);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, SupportTicketUser::class);
    }

    public function assignedIds()
    {
        return $this->assignees()->get()->pluck('id')->toArray();
    }

    public function getIsClosedAttribute($value)
    {
        if($this->status == "closed" || $this->status == "cancelled")
            return true;

        return false;
    }

    public function getAssigneeNamesAttribute()
    {
        return implode(', ', $this->assignees()->get()->pluck('fullname', 'id')->toArray());
    }

    public function assignee()
    {
        return $this->assignees()->first();
    }

    public function ticketComments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getDateCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format(env('Date_Format'). ' h:i A');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
