<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\SalaryBasic;
use App\SalaryLoan;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeLoanImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
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

        $loan  = SalaryLoan::firstWhere('reference', trim($row['reference'] ));

        $row['amount_remaining'] = $row['amount_remaining'] == null?$row['loan_amount']:$row['amount_remaining'];

        $data['employee_id'] = $employee->id;
        $data['loan_title'] = $row['loan_title'];
        $data['loan_type'] = "Staff Loan";
        $data['loan_time'] = $row['loan_term'];
        $data['amount_remaining'] = $row['amount_remaining'];
        $data['loan_amount'] = $row['loan_amount'];
        $data['time_remaining'] = number_format(($row['amount_remaining'] / $row['monthly_payable']), 0);
        $data['monthly_payable'] = $row['monthly_payable'];

        if(isset($loan))
        {
            $loan->update($data);
            return null;
        }

        $reference = (trim($row['reference'] ) == null)?generate_token():trim($row['reference']);
        $data['reference'] = $reference;

        return new SalaryLoan($data);
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
            'loan_amount' => 'required|between:1,999999.99',
            'monthly_payable' => 'required|between:1,999999.99',
        ];
    }
}
