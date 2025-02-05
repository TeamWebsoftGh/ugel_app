<?php

namespace Database\Seeders;

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

        $this->call(OrganizationSeeder::class);
        $this->call(LogsSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(PropertyTableSeeder::class);
        $this->call(ConfigurationsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
