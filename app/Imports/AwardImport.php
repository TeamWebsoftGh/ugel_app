<?php

namespace App\Imports;

use App\Models\Common\AwardType;
use App\Models\Employees\Employee;
use App\Models\Property\Award;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class AwardImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get or create the property-types type category
        $category = AwardType::firstOrCreate(['award_name' => $row['award_type']]);

        // Format the property-types date
        $award_date = format_excel_date(trim($row['award_date']));

        // Find the employee by staff ID
        $employee = Employee::firstWhere('staff_id', $row['staff_id']);

        // If employee doesn't exist, return early
        if (!$employee) {
            return null;
        }

        // Create a new property-types
        return new Award([
            'employee_id' => $employee->id,
            'award_type_id' => $category->id,
            'company_id' => $employee->company_id,
            'created_by' => user()->id,
            'award_information' => trim($row['award_information']),
            'cash' => trim($row['cash']),
            'gift' => trim($row['gift']),
            'award_date' => $award_date,
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
            'award_type' => 'required',
            'award_information' => 'required',
            'award_date' => 'required',
        ];
    }
}
