<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'business_phone' => $this->business_phone,
            'business_email' => $this->business_email,
            'client_type' => $this->clientType->name,
            'client_category' => $this->clientType->category,
        ];
    }
}
