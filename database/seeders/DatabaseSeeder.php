<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(LogsSeeder::class);
        $this->call(PropertyTableSeeder::class);
        $this->call(ConfigurationsSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
