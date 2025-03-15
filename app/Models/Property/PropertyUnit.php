<?php

namespace App\Models\Property;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyUnit extends Model
{
    protected $fillable = [
        'unit_name',
        'total_bedroom',
        'total_bathroom',
        'total_kitchen',
        'total_rooms',
        'general_rent',
        'deposit_type',
        'security_deposit',
        'late_fee',
        'late_fee_type',
        'incident_receipt',
        'rent_type',
        'monthly_due_pay',
        'yearly_due_pay',
        'lease_start_date',
        'lease_end_date',
        'lease_payment_due_date',
        'description',
        'square_feet',
        'amenities',
        'parking',
        'condition',
        'status',
        'is_active',
        'rent_amount',
        'unit_floor',
        'square_foot',
        'property_id'

    ];

    public function getUpdatedAtAtAttribute($value)
    {
        return Carbon::parse($value)->format(env('Date_Format'));
    }

    public function property()
    {
        return $this->belongsTo(Property::class)->withDefault(['name' => 'N/A']);
    }
}
