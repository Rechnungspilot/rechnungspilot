<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompaniesTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersTableSeeder::class,
            ContactsTableSeeder::class,
            // BanksTableSeeder::class,
            // BankCompanyTableSeeder::class,
        ]);
    }
}
