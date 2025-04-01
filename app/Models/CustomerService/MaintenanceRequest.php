<?php

namespace App\Models\CustomerService;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Common\Comment;
use App\Models\Common\Priority;
use App\Models\Property\Property;
use App\Models\Property\PropertyUnit;
use App\Models\Property\Room;

class MaintenanceRequest extends Model
{
    //
    protected $fillable = [
        'client_id',
        'reference',
        'property_id',
        'property_unit_id',
        'room_id',
        'user_id',
        'note',
        'description',
        'client_number',
        'client_phone_number',
        'maintenance_category_id',
        'client_email',
        'other_issue',
        'location',
        'is_notify',
        'completed_at',
        'closed_at',
        'priority_id',
        'company_id',
        'created_by',
        'status'
    ];
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function categories()
    {
        return $this->belongsToMany(
            MaintenanceCategory::class,
            'maintenance_category_maintenance_requests',
            'maintenance_id',
            'maintenance_category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, MaintenanceRequestUser::class, 'maintenance_requests_id', 'user_id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, MaintenanceRequestUser::class, 'maintenance_requests_id', 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault();
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class)->withDefault();
    }

    public function room()
    {
        return $this->belongsTo(Room::class)->withDefault();
    }

    public function maintenanceCategory()
    {
        return $this->belongsTo(MaintenanceCategory::class)->withDefault();
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class)->withDefault();
    }


}
