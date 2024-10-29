<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\SalaryBasic;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BasicSalaryImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = Employee::firstWhere('staff_id', $row['staff_id']);

        if(!$employee)
        {
            return null;
        }

        if(isset($row['is_tax_payer']))
        {
            $employee->is_tax_payer = strtolower($row['is_tax_payer']) == "yes"?1:0;
        }

        if(isset($row['is_ssnit_contributor']))
        {
            $employee->is_tax_payer = strtolower($row['is_ssnit_contributor']) == "yes"?1:0;
        }

        $employee->payslip_type = $row['payslip_type'];
        $employee->basic_salary = $row['basic_salary'];
        $employee->ssn = $row['ssnit_number'];
        $employee->update();

        if($employee->basic_salary == $row['basic_salary'])
        {
            return null;
        }

        $employee->payslip_type = $row['payslip_type'];
        $employee->basic_salary = $row['basic_salary'];
        $employee->ssn = $row['ssnit_number'];
        $employee->update();

        return new SalaryBasic([
            'company_id' => company_id(),
            'created_by' => user_id(),
            'employee_id' => $employee->id,
            'basic_salary' => $row['basic_salary'],
            'payslip_type' => $row['payslip_type'],
            'start_date' => Carbon::now()->startOfMonth()->format(env('Date_Format')),
            'end_date' => Carbon::now()->addDecades(6)->format(env('Date_Format'))
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
            'payslip_type' => 'required',
            'basic_salary' => 'required|between:0,999999.99',
        ];
    }
}
