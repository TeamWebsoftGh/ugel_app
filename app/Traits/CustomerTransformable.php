<?php

namespace App\Traits;

use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Repositories\CurrencyRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait CustomerTransformable
{
    /**
     * Transform the product
     *
     * @param User $customer
     * @return array
     */
    protected function transformClient(User $customer): array
    {
        $prod = [];
        $prod['first_name'] = $customer->first_name;
        $prod['last_name'] = $customer->last_name;
        $prod['middle_name'] = $customer->middle_name;
        $prod['fullname'] = $customer->fullname;
        $prod['username'] = $customer->username;
        $prod['email'] = $customer->email;
        $prod['phone_number'] = $customer->phone_number;
        $prod['gender'] = $customer->gender;
        $prod['force_password_reset'] = $customer->ask_password_reset;
        $prod['client_type'] = optional($customer->client->clientType)->name??"individual";
        $prod['phone_verified_at'] = $customer->phone_verified_at;
        $prod['last_active'] = $customer->last_active;
        $prod['last_active_ip'] = request()->ip();
        $prod['image'] = $customer?->photo;

        return $prod;
    }
}
