<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_date' => $this->payment_date,
            'status' => $this->status,
            'amount' => $this->amount,
            'description' => $this->description,
            'client_id' => $this->client_id,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'invoice_id' => $this->invoice_id,
            'invoice' => new InvoiceResource($this->whenLoaded('invoice')),
            'created_at' => $this->created_at,
        ];
    }
}
