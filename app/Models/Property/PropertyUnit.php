<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory;


    protected $fillable = [
        'unit_name',
        'bedroom',
        'bathroom',
        'kitchen',
        'total_rooms',
        'general_rent',
        'security_deposit',
        'late_fee',
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
