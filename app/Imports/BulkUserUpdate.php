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
use App\Models\OfficeShift;
use App\Models\Role;
use App\Models\Subsidiary;
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

class BulkUserUpdate implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

	use Importable;

    public function model(array $row)
	{
        $department = Department::firstOrCreate(
            [ 'department_name' => $row['department'], 'company_id' => 1],
            [ 'company_id' => 1 ]
        );

        $company = Company::firstOrCreate(
            [ 'company_name' => $row['subsidiary'] ],
            [
                'company_type' => 'corporation',
                'trading_name' => $row['subsidiary'],
                'registration_no' => '12345678',
                'staff_id_prefix' => 'BTL',
                'working_hours_per_day' => 8,
                'working_hours_per_month' => 176,
                'working_days_per_month' => 22,
                'probation_period' => 3,
                'contact_no' => '030232323',
                'email' => 'info@company.com',
                'state' => 'Greater Accra',
                'country_id' => 1,
                'currency_id' => 1,
            ]
        );

        $emp = Employee::firstWhere('staff_id', $row['staff_id']);

        if($emp != null)
        {
            $status = EmployeeType::firstOrCreate(
                ['emp_type_name' => $row['employee_status'], 'company_id' => 1],
                ['company_id' => 1]
            );

            $shift = OfficeShift::firstOrCreate(
                ['shift_name' => 'Morning Shift', 'company_id' => $emp->company_id],
                [
                    'shift_name' => 'Morning Shift',
                    'company_id' => $emp->company_id,
                    'default_shift' => 1,
                    'monday_in' => '08:00AM',
                    'monday_out' => '05:00PM',
                    'tuesday_in' => '08:00AM',
                    'tuesday_out' => '05:00PM',
                    'wednesday_in' => '08:00AM',
                    'wednesday_out' => '05:00PM',
                    'thursday_in' => '08:00AM',
                    'thursday_out' => '05:00PM',
                    'friday_in' => '08:00AM',
                    'friday_out' => '05:00PM',
                ]
            );

            $cat = EmployeeCategory::firstOrCreate(
                [ 'name' => $emp->employeeCategory->name, 'company_id' => 1],
            );

//            $emp->department_id = $department->id;
            $emp->employee_type_id = $status->id;
            $emp->employee_category_id = $cat->id;
            $emp->office_shift_id = $shift->id;
           // $emp->company_id = $company->id;
            $emp->save();
            User::find($emp->id)->update(['company_id' => $company->id]);
        }

		//return $emp;
	}

	public function rules(): array
	{
		return [
			'staff_id' => 'required',
			'employee_status' => 'required',
			'subsidiary' => 'required',
		];
	}

	public function chunkSize(): int
	{
		return 500;
	}

	public function batchSize(): int
	{
		return 1000;
	}
}
