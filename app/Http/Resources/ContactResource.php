<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'other_names' => $this->other_names,
            'full_name' => $this->fullname, // This uses the accessor from the model
            'email' => $this->email,
            'company' => $this->company,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth, // This will use the accessor to format the date
            'contact_group_name' => $this->contact_group->name,
        ];
    }
}
