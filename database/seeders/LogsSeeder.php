<?php

namespace Database\Seeders;

use App\Models\Audit\LogAction;
use App\Models\Audit\LogType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LogType::create([
            'name' => 'Main Activity',
            'slug' => Str::slug('Main Activity'),
        ]);

        LogType::create([
            'name' => 'Login Activity',
            'slug' => Str::slug('Login Activity'),
        ]);

        LogType::create([
            'name' => 'User Activity',
            'slug' => Str::slug('User Activity'),
        ]);

        LogType::create([
            'name' => 'Customer Activity',
            'slug' => Str::slug('Customer Activity'),
        ]);

        LogType::create([
            'name' => 'Hr Activity',
            'slug' => Str::slug('Hr Activity'),
        ]);


        //Log Actions
        LogAction::create([
            'name' => 'Login Successful',
            'slug' =>  Str::slug('Login Successful'),
            'log_type_id' => 2,
        ]);

        LogAction::create([
            'name' => 'Login Failed',
            'slug' =>  Str::slug('Login Failed'),
            'log_type_id' => 2,
        ]);

        //User
        LogAction::create([
            'name' => 'Create User Successful',
            'slug' =>  Str::slug('Create User Successful'),
            'log_type_id' => 3,
        ]);

        LogAction::create([
            'name' => 'Update User Successful',
            'slug' =>  Str::slug('Update User Successful'),
            'log_type_id' => 3,
        ]);

        LogAction::create([
            'name' => 'Delete User Successful',
            'slug' =>  Str::slug('Delete User Successful'),
            'log_type_id' => 3,
        ]);

        LogAction::create([
            'name' => 'Create User Failed',
            'slug' =>  Str::slug('Create User Failed'),
            'log_type_id' => 3,
        ]);

        LogAction::create([
            'name' => 'Update User Failed',
            'slug' =>  Str::slug('Update User Failed'),
            'log_type_id' => 3,
        ]);

        LogAction::create([
            'name' => 'Delete User Failed',
            'slug' =>  Str::slug('Delete User Failed'),
            'log_type_id' => 3,
        ]);
    }
}
