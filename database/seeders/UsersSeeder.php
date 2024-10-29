<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Organization\Company;
use App\Models\Organization\Department;
use App\Models\Organization\Designation;
use App\Models\Organization\EmployeeType;
use App\Models\Organization\Branch;
use App\Models\Organization\OfficeShift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company_id = 1;
        //Create Users and Roles
        $user =User::create([
            'first_name'=>'developer',
            'username'=>'developer',
            'email'=>'jerryjohnc1@gmail.com',
            'password'=> bcrypt('password@12'),
            'phone_number'=> '0245555555',
            'is_builtin' => 1,
            'company_id' => $company_id,
        ]);

        $superUser = Role::where('name', 'developer')->first();
        $user->roles()->save($superUser);

        //Create Users and Roles
        $user2 = User::create([
            'first_name'=>'admin',
            'username'=>'admin',
            'email'=>'admin@erp.com',
            'password'=> bcrypt('password'),
            'phone_number'=> '0245555555',
            'is_builtin' => 1,
            'company_id' => $company_id,
        ]);

        $adminUser = Role::where('name', 'admin')->first();
        $user2->roles()->save($adminUser);

    }
}
