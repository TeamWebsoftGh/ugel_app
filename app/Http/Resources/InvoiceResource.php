<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'sub_total' => $this->sub_total_amount,
            'total' => $this->total_amount,
            'client_id' => $this->client_id,
            'booking_id' => $this->booking_id,
            'booking' => new BookingResource($this->whenLoaded('booking')),
          //  'items' => InvoiceItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
