<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property_name' => $this->property->property_name,
            'property_id' => $this->property->id,
            'rating' => $this->rating,
            'subject' => $this->subject,
            'comment' => $this->comment,
            'user' => $this->comment,
            'user_image' => asset($this->owner->user_image),
            'created_at' => $this->created_at,
        ];
    }
}
