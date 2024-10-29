<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use App\Models\SalaryBasic;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeBankAccountImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
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

        if($row['account_number'] == 'null' || $row['account_number'] = null)
        {
            return null;
        }


        $bank = $employee->banks()->find($row['employee_bank_id']);
        if($bank != null){
            $bank->account_number = trim($row['account_number']);
            $bank->bank_branch = trim($row['bank_branch']);
            $bank->bank_name = trim($row['bank_name']);
            $bank->account_title = trim($row['full_name']);
            $bank->account_type = trim($row['account_type']);
            $bank->is_primary = 1;
            $bank->percentage_pay = trim($row['percentage_pay']);

            $bank->save();

            return null;
        }

        return new EmployeeBankAccount([
            'employee_id' => $employee->id,
            'account_number' => trim($row['account_number']),
            'bank_name' => trim($row['bank_name']),
            'bank_branch' => trim($row['bank_branch']),
            'account_title' => trim($row['full_name']),
            'percentage_pay' => trim($row['percentage_pay']),
            'account_type' => trim($row['account_type']),
            'is_primary' => 1,
            'status' => 'approved',
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
            'bank_name' => 'required_with:account_number',
            'bank_branch' => 'required_with:account_number',
        ];
    }
}
