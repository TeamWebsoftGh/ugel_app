<?php

namespace Database\Seeders;

use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Organization\Designation;
use App\Models\Organization\EmployeeCategory;
use App\Models\Organization\EmployeeType;
use App\Models\Organization\Branch;
use App\Models\Timesheet\OfficeShift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $company_id = $this->command->argument('company')??1;
        $company_id = 1;

        Company::create([
            'company_name' => 'UGEL APP',
            'company_code' => 'UGEL APP',
            'company_type' => 'corporation',
            'trading_name' => 'UGEL APP',
            'registration_number' => '12345678',
            'contact_no' => '030232323',
            'email' => 'info@company.com',
            'city' => 'Accra',
            'state' => 'Greater Accra',
            'country_id' => 1,
            'currency_id' => 1,
        ]);}
}
