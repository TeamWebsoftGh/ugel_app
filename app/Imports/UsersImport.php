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

class UsersImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

	use Importable;

    public function model(array $row)
	{
        $company = null;
        $subsidiary = null;
        $mc = settings("enable_multi_company", false);
        $country = optional(Country::firstWhere('name', trim($row['country'])))->id;

        if ($mc && isset( $row['subsidiary'])){
            $company = Company::firstOrCreate(
                [ 'company_name' => $row['subsidiary'] ],
                [
                    'company_type' => 'corporation',
                    'trading_name' => $row['subsidiary'],
                    'registration_no' => '12345678',
                    'staff_id_prefix' => 'AL',
                    'working_hours_per_day' => 8,
                    'working_hours_per_month' => 176,
                    'working_days_per_month' => 22,
                    'probation_period' => 3,
                    'contact_no' => '030232323',
                    'email' => 'info@company.com',
                    'city' => trim($row['city']),
                    'state' => 'Greater Accra',
                    'country_id' => $country??1,
                    'currency_id' => 1,
                ]
            );
            $subsidiary = Subsidiary::firstOrCreate(
                [ 'name' => $row['subsidiary'], 'company_id' => $company->id ],
            );
        }
        $company_id = optional($company)->id??user()->company_id;
		$type = !Str::contains(trim($row['staff_type']), 'expat');
		$joining_date = format_excel_date(trim($row['date_of_joining']));
        $date_of_birth = format_excel_date(trim($row['date_of_birth']));
        $endDate = null;

        $status = EmployeeType::firstOrCreate(
            [ 'emp_type_name' => $row['status'], 'company_id' => $company_id ],
        );

        $cat = EmployeeCategory::firstOrCreate(
            [ 'name' => $row['category'], 'company_id' => $company_id ],
        );

        $department = Department::firstOrCreate(
            [ 'department_name' => $row['department'], 'company_id' => $company_id ],
            [ 'company_id' => $company_id ]
        );

        $branch = Location::firstOrCreate(
            [ 'location_name' => $row['branch']],
            [ 'company_id' => $company_id,  'country' => $country??1]
        );

        $designation = Designation::firstOrCreate(
            [ 'designation_name' => $row['designation'], 'company_id' => $company_id ],
            [ 'department_id' => $department->id, 'company_id' => $company_id, 'max_staff_count' => 2 ]
        );

        try {
            $startDate = Carbon::createFromFormat(env('Date_Format'), $joining_date);
            $endDate = Carbon::parse($startDate->addMonths($company->probation_period?? 3))->format(env('Date_Format'));
        }
        catch (Exception $ex)
        {

        }

		$user = User::create([
			//
			'username' => Str::random(8),
			'name' => $row['first_name'],
			'email' => $row['email'],
			'contact_no' => $row['contact_no'],
			'password' =>  Hash::make("password"),
			'role_users_id' => 2,
			'company_id' => $company_id
		]);

        $staffId =  $row['staff_id']  == ''? generate_staff_id():$row['staff_id'];
        $user->username = $staffId;
        $user->save();
        $user->syncRoles(array(optional(Role::firstWhere('name', 'employee'))->id));

		return new Employee([
			'id' => $user->id,
			'staff_id' => trim($staffId),
			'first_name' => trim($row['first_name']),
			'last_name' => trim($row['last_name']),
			'other_names' => trim($row['other_names']),
			'email' => trim($row['email']),
			'contact_no' => '0'.$row['contact_no'],
			'basic_salary' => $row['basic_salary'],
			'ssn' => trim($row['ssnit_no']),
			'national_id' => trim($row['national_id']),
			'tin' => trim($row['national_id']),
			'location_id' => $branch->id,
			'hometown' => trim($row['hometown']),
			'religion' => trim($row['religion']),
			'region' => trim($row['region']),
			'maiden_name' => trim($row['maiden_name']),
			'nationality' => trim($row['nationality']),
			'marital_status' => strtolower(trim($row['marital_status'])),
			'place_of_birth' => trim($row['place_of_birth']),
			'number_of_children' => isset($row['number_of_children'])?trim($row['number_of_children']):null,
			'is_local' => $type,
			'is_ssf_contributor' => $status->pays_ssf??$type,
			'is_tax_payer' => $status->pays_tax??$type,
			'designation_id' => $designation->id,
			'department_id' => $department->id,
			'subsidiary_id' => isset($subsidiary)?$subsidiary->id:null,
			'employee_type_id' => $status->id??1,
			'employee_category_id' => $cat->id??2,
			'joining_date' => $joining_date,
			'probation_start_date' => $joining_date,
			'probation_end_date' => $endDate,
			'confirmed_date' => $endDate,
			'date_of_birth' => $date_of_birth,
			'gender' => trim(Str::lower($row['gender'])),
			'title' => preg_replace("/[^a-z]+/", "", Str::lower(trim($row['title']))),
			'address' => $row['address'],
			'city' => trim($row['city']),
			'country' => $country??1,
			'zip_code' => trim($row['zip']),
			'office_shift_id' => 1,
			'payslip_type' => 'monthly',
			'status' => 'approved',
			'company_id' => $company_id,
			'role_users_id' => 2,
			'user_id' => $user->id
		]);
	}

	public function rules(): array
	{
		return [
			'first_name' => 'required',
			'last_name' => 'required',
			'date_of_birth' => 'required',
			'department' => 'required',
			'designation' => 'required',
//			'date_of_joining' => 'required',
			'email' => 'nullable|unique:users,email',
			'username' => 'nullable|unique:users,username'
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
