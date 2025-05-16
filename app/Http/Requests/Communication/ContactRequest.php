<?php

namespace App\Http\Requests\Communication;


use App\Abstracts\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required|unique:contacts,phone_number,'.$this->request->get("id"),
            'contact_group_id' => 'required',
            'first_name' => 'required',
        ];
    }
}
