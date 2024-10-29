<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Country;
use App\Models\Employee;
use App\Models\EmployeeContact;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactPersonImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return
     */
    public function model(array $row)
    {
        $employee = Employee::firstWhere('staff_id', $row['staff_id']);
        $type = Str::slug(trim($row['type']));
        if(!$employee)
            return;

        $gh = EmployeeContact::Where(['employee_id' => $employee->id, 'contact_name' => trim($row['contact_name']), 'type' => $type])->get();
        if(count($gh) > 0)
            return;

        $country = optional(Country::firstWhere('name', trim($row['country'])))->id;
        $is_living = !(Str::lower(trim($row['is_living'])) == "no");

        return new EmployeeContact([
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'contact_name' => trim($row['contact_name']),
            'type' => $type,
            'relation' => strtolower(trim($row['relation'])),
            'personal_email' => trim($row['email']),
            'address1' => trim($row['address']),
            'personal_phone' => '0'.trim($row['phone_number']),
            'state' => trim($row['region']),
            'gender' => strtolower(trim($row['gender'])),
            'city' => trim($row['city']),
            'country_id' => $country??1,
            'is_living' => $is_living,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            'staff_id' => 'required',
            'contact_name' => 'required',
        ];
    }
}
