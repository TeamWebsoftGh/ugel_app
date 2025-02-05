<?php

namespace App\Http\Requests\Property;


use App\Abstracts\Http\FormRequest;

class AmenityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:amenities,name',
        ];
    }
}
