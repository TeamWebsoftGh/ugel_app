<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'client_id' => 'nullable|exists:clients,id',
            'property_id' => 'required',
            'priority_id' => 'required',
            'client_email' => 'required',
            'client_phone_number' => 'required',
            'maintenance_category_id' => 'required',
            'description' => 'required',
        ];
    }
}
