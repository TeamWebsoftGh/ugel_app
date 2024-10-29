<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
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
            'user_id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'role_name' => $this->role_name,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'photo' => asset($this->UserImage),
            'company_id' => $this->company_id,
            'is_active' => $this->is_active,
            'last_login_ip' => $this->last_login_ip,
            'last_login_date' => $this->last_login_date,
            'ask_password_reset' => $this->ask_password_reset,
            'last_password_reset' => $this->last_password_reset,
            'created_at' => $this->created_at,
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
