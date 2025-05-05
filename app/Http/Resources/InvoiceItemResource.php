<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
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
            'invoice_item_lookup_id' => $this->invoice_number,
            'amount' => $this->amount,
            'formated_amount' => format_money($this->amount),
            'quantity' => $this->quantity,
            'item_name' => $this->invoiceItemLookup->name,
            'total' => $this->quantity*$this->amount,
            'formated_total' => format_money($this->quantity*$this->amount),
            'created_at' => $this->created_at,
        ];
    }
}
