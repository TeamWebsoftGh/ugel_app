<?php

namespace App\Imports;

use App\Abstracts\Import;
use App\Models\Communication\Contact as Model;
use App\Models\Communication\ContactGroup;

class ContactsImport extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);
        $contact = ContactGroup::firstOrCreate([
            'name'            => $row['contact_group'],
        ], [
            'company_id'      => company_id(),
            'created_by'      => user_id(),
            'created_from'    => 'import',
        ]);

        $row['contact_group_id'] = $contact->id;
        return $row;
    }

    public function rules(): array
    {
        return [
            'contact_group' => 'required',
            'first_name' => 'required',
            'phone_number' => 'required|unique:contacts,phone_number',
        ];
    }
}
