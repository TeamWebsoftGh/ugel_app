<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'booking_id' => $this->id,
            'booking_number' => $this->booking_number,
            'booking_date' => $this->booking_date,
            'lease_start_date' => $this->lease_start_date,
            'lease_end_date' => $this->lease_end_date,
            'status' => $this->status,
            'sub_total' => $this->sub_total,
            'formated_sub_total' => format_money($this->sub_total),
            'total_price' => $this->total_price,
            'formatted_total_price' => format_money($this->total_price),
            'total_paid' => $this->total_paid,
            'formatted_total_paid' => format_money($this->total_paid),
            'client_id' => $this->client_id,
            'booking_period_id' => $this->booking_period_id,
            'booking_period_name' => $this->bookingPeriod->name??"N/A",
            'property_id' => $this->property_id,
            'property_name' => $this->propertyUnit->property->property_name??"N/A",
            'property_unit_id' => $this->property_unit_id,
            'property_unit_name' => $this->propertyUnit->unit_name??"N/A",
            'room_id' => $this->room_id,
            'room_name' => $this->room->room_name,
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'invoice_total_amount' => $this->invoice->total_amount,
            'created_at' => $this->created_at,
        ];
    }
}
