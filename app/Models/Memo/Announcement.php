<?php

namespace App\Models\Memo;

use App\Abstracts\Model;
use App\Models\Client\ClientType;
use App\Models\Property\Property;
use App\Models\Property\PropertyType;

class Announcement extends Model
{
	protected $fillable = [
		'title',
        'start_date',
        'end_date',
        'short_message',
        'message',
        'is_notify',
        'property_id',
        'property_type_id',
        'client_type_id',
        'is_sent',
        'created_by',
        'created_from',
        'is_active',
        'type',
        'company_id',
        'file',
        'file_type',
        'tem_type',
        'gender',
        'min_age',
        'max_age',
	];

	public function property()
    {
		return $this->belongsTo(Property::class)->withDefault();
	}

    public function property_type()
    {
        return $this->belongsTo(PropertyType::class)->withDefault();
    }

    public function client_type()
    {
        return $this->belongsTo(ClientType::class)->withDefault();
    }
}
