<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\EmployeeType;
use App\Models\Location;
use App\Models\Role;
use App\Models\Subsidiary;
use App\Models\Termination;
use App\Models\TerminationType;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PastEmployeesImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

	use Importable;

    public function model(array $row)
	{
        $exit_date = format_excel_date(trim($row['exit_date']));

        $employee = Employee::firstWhere(['email' => trim($row['email'])]);

		return new Termination([
            'terminated_employee' => $employee->id,
            'termination_date' => $exit_date,
            'notice_date' => $exit_date,
            'status' => "approved",
            'last_pay_date' => $exit_date,
        ]);
	}

	public function rules(): array
	{
		return [
			'email' => 'required',
		];
	}

//	public function customValidationAttributes()
//	{
//		return [
//			'email.required' => 'email',
//		];
//	}

	public function chunkSize(): int
	{
		return 500;
	}

	public function batchSize(): int
	{
		return 1000;
	}
}
