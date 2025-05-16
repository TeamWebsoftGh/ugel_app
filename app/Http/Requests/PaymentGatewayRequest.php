<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->request->has('paystack')) {
            return [
                'name' => 'required|unique:payment_gateways,id,id',
                'public_key' => 'required',
                'secret_key' => 'required',
                'base_url' => 'required',
                'merchant_email' => 'required',
            ];
        }

        if($this->request->has('stripe')) {
            return [
                'name' => 'required|unique:payment_gateways,id,id',
                'publishable_key' => 'required',
                'secret_key' => 'required',
                'base_url' => 'required',
            ];
        }

        if($this->request->has('offline')) {
            return [
                'name' => 'required|unique:payment_gateways,id,id',
                'description' => 'required',
                'instruction' => 'nullable|max:255',
            ];
        }
    }
}
