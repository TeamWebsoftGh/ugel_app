<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\PayDeduction;
use App\SalaryDeduction;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryDeductionImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = Employee::firstWhere('staff_id', $row['staff_id']);
        $deduction = PayDeduction::firstWhere('deduction_name', $row['payroll_item_name']);
        $start_date = format_excel_date(trim($row['start_date']));
        $end_date = format_excel_date(trim($row['end_date']));
        $is_rate = $row['installment_type'] == "rate" ? 1 : 0;
        $status = $row['status'] == "no" ? 0 : 1;

        if(!$employee || !$deduction)
        {
            return null;
        }
        $latest = SalaryDeduction::firstWhere(['employee_id' => $employee->id, 'pay_deduction_id' => $deduction->id]);

        if ($latest){
            $latest->is_active = 0;
            $latest->save();
        }
        return new SalaryDeduction([
            'company_id' => company_id(),
            'created_by' => user_id(),
            'employee_id' => $employee->id,
            'installment' => $row['installment'],
            'is_rate' => $is_rate,
            'pay_deduction_id' => $deduction->id,
            'is_active' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date
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
            'payroll_item_name' => 'required',
            'installment_type' => 'required',
            'installment' => 'required|between:0,999999.99',
            'start_date' => 'required',
            'end_date' => 'required',
            'is_active' => 'required',
        ];
    }
}
